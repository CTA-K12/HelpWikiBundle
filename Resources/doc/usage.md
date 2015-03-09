# MesdHelpWikiBundle Documentation

## Table of Contents

*. Pages

## Usage

### Pages

### Permissions

Permissions are defined as follows:

*. `VIEW`
*. `EDIT`
*. `CREATE`
*. `SHOW`
*. `EDIT`
*. `REMOVE`
*. `RESTORE`
*. `FLAG`
*. `APPROVE`
*. `RESTRICT`
*. `UPLOAD`
*. `MANAGE`

Permission are tied to the entities by both roles and users. Your application
must have entities for both and either implement the Symfony interfaces for
those entities or contain the minimum methods:

*. `User::getId()`
*. `User::getUsername()`
*. `Role::getId()`
*. `Role::getRole()`

Permissions use the Symfony VoterInterface to grant access to various actions
within the bundle. From the permissions menu, administrators may associate roles
or users with permissions on the following objects:

*. `Pages`
*. `Links`
*. `Comments`
*. `History`
*. `Permissions`
*. `Menus`

Security can also be assigned to individual pages or a structured set of pages.
Voters work on a first come basis when denying access to an action. An instance
of an object is first checked to see if it has any permissions applied directly
to it, next any instances of parent objects are checked (provided the instanced
object is structures), and lastly the object class is checked. If permission is
granted after all voting is accomplished, the user will have access to the
action on that object.

### Twig Extensions

#### The Linker Function

Include the linker function on any page to call a dynamic button widget allowing
users to view an existing page or create a new one for a route.

The Help Wiki Bundle can link pages to any named route alias in your application,
meaning that if you have a route alias named `AcmeDemoBundle_home` or
`AcmeUserBundle_user_edit`, that alias can be linked to `page-id = 42`.
Regardless of required parameters, Help Wiki only needs to know the alias to
create a link. A single Help Wiki page can be linked to multiple route aliases,
but a route alias may only link to one page. To invoke the linker extension,
simply use the following function in your Twig template:

`mesd_help_wiki_linker($opts)`

Without parameters, the linker has three states.

1. View a HelpWikiPage
2. Create a new HelpWikiPage
3. No HelpWikiPage found

The last two states are dependent upon permissions defined in the system. If you
have a role called `ROLE_CREATE_HELPWIKI_PAGE` and connect it to the Link
entity, users with that role will see the linker displayed as 'Create new page'.
But, if you deny users with the `ROLE_USER` role the ability to create new
pages, the linker will display a line of text reading 'No page found'.

If you choose not to assign permissions to the linker, you can always do so by
adding security in the options.

For example:

`mesd_help_wiki_linker({'CREATE': {'ROLES': ['ROLE_ADMIN', 'ROLE_MANAGER']}})`

Will exercise permissions on creating pages via the linker for users who do not
have either of those roles. Another example shows how to exclude users:

`mesd_help_wiki_linker({'CREATE': {'!USERS': ['john.doe', 'baduser99']}})`

Keep in mind, that permissions are not added to the system when these options
are created.

#### The Page Function

The Help Wiki Bundle doesn't only have to be about help pages. Any type of
standalone page can be created to serve an application's needs. With the page
function, static pages like an about, copyright, or privacy page can be created.
To create a link to a static page, include the following function in your Twig
template as shown:

mesd_help_wiki_page($id, $opts)

Notice that instead of using a page's slug, we use the page id. This is
important because a page's slug is more often likely to change than the id,
hence why it is used here. Like before, we can define in the options if we
would like to apply security to this link.

### Form Types

The Help Wiki Bundle comes with three form types out of the box:

*. WYSIWYG Editor (CKEditor)
*. Select (Select2)
*. Sortable-Nestable (Modified Wordpress jQuery Plugin)
*. File Upload (BlueImp Uploader)

These form types can be overridden in your config file and connected to any
form type service in your application.

If you choose to use the included form types, there are several configurable
options that may be set as well.

### Assets

The bundle comes with jquery, jquery-ui, and bootstrap-js libraries included.
But if you already use those libraries, you can point the assets in the bundle
to the predefined assets to avoid duplicating any unnecessary loading.

### 