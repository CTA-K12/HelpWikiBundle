$(document).ready(function() {
    $('#nestable').nestable();
});


/**
 * @Heartbeat
 *
 * Update slug after title has data and loses focus (every time)
 *
 * Envoke autosave after title has data and loses focus (only once)
 *
 * Autosave document, save as draft
 *
 * Autosave Feature or HeartBeat Feature
 * Every 5 minutes, or a time specified in config, update page.
 * Save status as AUTOSAVE
 *
 * If user exits page without saving, give a warning that they will lose all
 * data unless they save the page first.\
 *
 * If they choose not to save, remove page and all associated data.
 *
 * If they unexpectedly leave in a manner that the lostener is not envoked,
 * when they return to TOC or page screens, display error message that they
 * have unsaved pages. Display until they take action.
 *
 * If they have more than one, display each one.
 *
 * Anytime a page is saved manually by the user,
 * all autosaves for that page are deleted up until the save.
 *
 * If the last entry for a page in history is an autosave,
 * alert the user there is a more recent version of the page and give
 * them the option to switch to it or continue working on the original.
 *
 * When a page is first saved, whether autosaved or not;
 * set edit_in_progress to true.
 * Update every 5 minutes.
 *
 * When save is clicked, set edit_in_progress to false.
 *
 * Edit_in_progress is valid for 1 hour after final edit_in_progress
 * updated time.
 *
 * If user attempts to edit page with valid edit_in_progress, lock them out.
 * If user attempts to edit page with invalid edit_in_progress,
 * inform them that the page has previously been edited but has not been saved.
 * Warn them that they may unlock the page and the previous user will lose
 * all unsaved work.
 *
 */

$(document).on('heartbeat-send.refresh-lock', function( e, data ) {
    var lock = $('#active_post_lock').val(),
        post_id = $('#post_ID').val(),
        send = {};

    if ( ! post_id || ! $('#post-lock-dialog').length )
        return;

    send.post_id = post_id;

    if ( lock )
        send.lock = lock;

    data['wp-refresh-post-lock'] = send;

}).on( 'heartbeat-tick.refresh-lock', function( e, data ) {
    // Post locks: update the lock string or show the dialog if somebody has taken over editing
    var received, wrap, avatar;

    if ( data['wp-refresh-post-lock'] ) {
        received = data['wp-refresh-post-lock'];

        if ( received.lock_error ) {
            // show "editing taken over" message
            wrap = $('#post-lock-dialog');

            if ( wrap.length && ! wrap.is(':visible') ) {
                if ( wp.autosave ) {
                    // Save the latest changes and disable
                    $(document).one( 'heartbeat-tick', function() {
                        wp.autosave.server.suspend();
                        wrap.removeClass('saving').addClass('saved');
                        $(window).off( 'beforeunload.edit-post' );
                    });

                    wrap.addClass('saving');
                    wp.autosave.server.triggerSave();
                }

                if ( received.lock_error.avatar_src ) {
                    avatar = $('<img class="avatar avatar-64 photo" width="64" height="64" />').attr( 'src', received.lock_error.avatar_src.replace(/&amp;/g, '&') );
                    wrap.find('div.post-locked-avatar').empty().append( avatar );
                }

                wrap.show().find('.currently-editing').text( received.lock_error.text );
                wrap.find('.wp-tab-first').focus();
            }
        } else if ( received.new_lock ) {
            $('#active_post_lock').val( received.new_lock );
        }
    }
}).on( 'before-autosave.update-post-slug', function() {
    titleHasFocus = document.activeElement && document.activeElement.id === 'title';
}).on( 'after-autosave.update-post-slug', function() {
    // Create slug area only if not already there
    // and the title field was not focused (user was not typing a title) when autosave ran
    if ( ! $('#edit-slug-box > *').length && ! titleHasFocus ) {
        $.post( ajaxurl, {
                action: 'sample-permalink',
                post_id: $('#post_ID').val(),
                new_title: $('#title').val(),
                samplepermalinknonce: $('#samplepermalinknonce').val()
            },
            function( data ) {
                if ( data != '-1' ) {
                    $('#edit-slug-box').html(data);
                }
            }
        );
    }
});