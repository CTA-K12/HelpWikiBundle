HelpWikiBundle
==============

Wiki bundle for adding documentation to pages in an application.

version: 0.0.1

Description
-----------

HelpWikiBundle is a wiki style documentation project aimed at providing
front-end assistance in web-applications. HelpWiki pages are created by
users and administrators. These pages are linked to route aliases which
allow a user to view specific help documentation based on their location
in the application. Some of the features of this bundle are:

  + create, edit, and delete pages
  + page history and revision
  + page security based on user roles
  + table of contents
  + organization of contents for reading/printing like a book
  + create, edit, and delete page comments
  + ckeditor for rich text editing
  + drag and drop image/document attaching
  + create static pages for use anywhere in an application (.i.e about, license, terms)

We have more plans for this bundle, mostly to make it more dependency free
and feature-rich. Our goal is for users to not only use the documentation,
but to pass their knowledge on to others as they use an application.

Screenshot: not yet.

Installing
----------

How to install.

This bundle is dependent on the following bundles:

  + symfony 2.3+
  + twig 2.1+

This bundle has been developed and tested to work on the following broswers:

  + Chrome
  + Foxfire
  + Safari
  + Opera
  + Android
  + Opera Mini
  + Blackberry
  + and sadly some Internet Explorer

.. code-block:: bash

    $ cd /var/www/html/project
    $ composer install

Configuring
-----------

No configuration instructions yet.

Updating
--------

How to update.

Usage
-----

How to use.


Code Snippet
------------

.. code-block:: html

    <body>
        <h1 class="your-class">code</h1>
        <p>some code here</p>
    </body>


Troubleshooting
---------------

This bundle is likely to fail if you attempt to install it.
I must work on this more.

History
-------

2014/04/09 - initial commit, probably several problems
