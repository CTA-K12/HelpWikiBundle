# HelpWikiBundle

Wiki bundle for adding documentation to pages in an application.

version: `0.0.2`

## Description

HelpWikiBundle is a wiki style documentation project aimed at providing
front-end assistance in web-applications. HelpWiki pages are created by
users and administrators. These pages are linked to route aliases which
allow a user to view specific help documentation based on their location
in the application.

A problem that plagues many enterprise-level applications is the complete lack
of end-user documentation. The Help Wiki gives everyone (including developers,
project managers, administrators, technical-writers, and end-users) the ability
to contribute to a wiki to help other users better understand the system they
are using.

Some of the features of this bundle are:

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

## Installing

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
```

## Configuration

### UserInterface and RoleInterface

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
            Mesd\HelpWikiBundle\Model\UserSubjectInterface: Acme\DemoBundle\Entity\User
            Mesd\HelpWikiBundle\Model\RoleSubjectInterface: Acme\DemoBundle\Entity\Role
```

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
            <doctrine:resolve-target-entity interface="Mesd\HelpWikiBundle\Model\UserSubjectInterface">
              Mesd\Acme\DemoBundle\Entity\AppUser
            </resolve-target-entity>
            <doctrine:resolve-target-entity interface="Mesd\HelpWikiBundle\Model\RoleSubjectInterface">
              Mesd\UserBundle\Entity\AuthRole
            </resolve-target-entity>
        </doctrine:orm>
    </doctrine:config>
</container>
```

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
```
REMEMBER! Permission are tied to the entities by both roles and users. Your application
must have entities for both and either implement the Symfony interfaces for
those entities or contain the minimum methods:

  + `User::getId()`
  + `User::getUsername()`
  + `Role::getId()`
  + `Role::getRole()`

# Configure Form Types

### Form Types

#### Select Box Form Type

#### Sortable-Nestable List Form Type

#### WYSIWYG Text Editor

## Integrating Wiki Pages Into Your Application

## Updating

How to update.

## Usage

Help Wiki uses a combination of twig extensions and templates for output. Any
template can be overriden by simply copying the template directory into your
`app/Resources/views` folder. Twig extension blocks are overriden by configuring
your blocks in your `app/config/config.yml` file.  

By default all of the bundles templates extend the your application's 
'::index.html.twig'.

### Twig Extensions

The following extensions are at your disposal:

  + `mesd_help_wiki_linker($opts)`
  + `mesd_help_wiki_page($id, $opts)`

The linker extension is the best way to fully integrate your application with
the wiki. One of the biggest reasons the bundle was written was to give users
page specific on-screen documentation. The linker does just that. When
implemented, a button will display whether or not a help wiki page exists for
the screen the user is on. If a help wiki page exists, the linker will provide
a link to that documentation. If it doesn't, the linker will allow a user to
create one.

screen. 
### Permissions

By default, all security is turned off. This is easily changed by setting the
configuration value `security` to true in your `app/config/config.yml` file.
In addition, you can also set an array of roles with full access. See below:

```yaml
# Mesd Help Wiki Configuration
mesd_help_wiki:
    security: true
    super_admin_roles: [ROLE_ADMIN]
```

By default, no roles are populated. If you turn security ON and have not
configured any permissions from the GUI or set a `super_admin_role` you will
not have access to anything!

The way security works in Help Wiki is that you have no permissions till
granted. Each action in the bundle is configurable via security.

## Troubleshooting


## Contributing

See the [CONTRIBUTING.md](CONTRIBUTING.md) file for more information.

## Changelog

See the [CHANGELOG.md](CHANGELOG.md) file for more information.

## License

See the [LICENSE.md](LICENSE.md) file for more information.
