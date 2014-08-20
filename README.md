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

```bash
    $ cd /var/www/html/project
    $ composer install

Configuring
-----------

# Configure UserInterface and RoleInterface

Before using this bundle, you will need to set up your users and roles.
This is done by configuring your `app/config/config.ext` to link the
HelpWikiBundle abstract user and role classes to your application's
user and role entities:

```yaml
# Doctrine Configuration
doctrine:
    # ...
    orm:
        # ...
        resolve_target_entities:
            Mesd\HelpWikiBundle\Model\UserSubjectInterface: Mesd\Acme\DemoBundle\Entity\AppUser
            Mesd\HelpWikiBundle\Model\RoleSubjectInterface: Mesd\UserBundle\Entity\AuthRole

```xml
<!-- app/config/config.xml -->
<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:doctrine="http://symfony.com/schema/dic/doctrine"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd
                        http://symfony.com/schema/dic/doctrine http://symfony.com/schema/dic/doctrine/doctrine-1.0.xsd">
    <doctrine:config>
        <doctrine:orm>
            <!-- ... -->
            <doctrine:resolve-target-entity interface="Mesd\HelpWikiBundle\Model\UserSubjectInterface">Mesd\Acme\DemoBundle\Entity\AppUser</resolve-target-entity>
            <doctrine:resolve-target-entity interface="Mesd\HelpWikiBundle\Model\RoleSubjectInterface">Mesd\UserBundle\Entity\AuthRole</resolve-target-entity>
        </doctrine:orm>
    </doctrine:config>
</container>

```php
// app/config/config.php
$container->loadFromExtension('doctrine', array(
    'orm' => array(
        // ...
        'resolve_target_entities' => array(
            'Mesd\HelpWikiBundle\Model\UserSubjectInterface' => 'Mesd\Acme\DemoBundle\Entity\AppUser',
            'Mesd\HelpWikiBundle\Model\RoleSubjectInterface' => 'Mesd\UserBundle\Entity\AuthRole',
        ),
    ),
));

# Configure Form Types

## Configure Select 2 Form Type

## Configure Sortable-Nestable List Form Type

## Configure WYSIWYG Text Editor

# Integrating Wiki Pages Into Your Application

The HelpWikiBundle associates 

Updating
--------

How to update.

Usage
-----

How to use.


Code Snippet
------------

```html
    <body>
        <h1 class="your-class">code</h1>
        <p>some code here</p>
    </body>


Troubleshooting
---------------

This bundle is likely to fail if you attempt to install it.
I must work on this more.


## Contributing

See the [CONTRIBUTING.md](CONTRIBUTING.md) file for more information.

## Changelog

See the [CHANGELOG.md](CHANGELOG.md) file for more information.

## License

See the [LICENSE.md](LICENSE.md) file for more information.