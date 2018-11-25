TangoMan Callback Twig Extension Bundle
=======================================

**TangoMan Callback Twig Extension Bundle** provides simple callback manager for symfony projects.

For example, anytime you need your users to fill a form from a paginated list, your controller will have to redirect them to the page they originated from and you don't want them to loose their parameters from query string.

**TangoMan Callback Twig Extension Bundle** provides simple callback function for twig which avoids unnecessary callbacks to stack up indefinitely in users query string.

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
composer require tangoman/callback-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new TangoMan\CallbackBundle\TangoManCallbackBundle(),
        );

        // ...
    }
}
```

You don't have to add **TangoMan CallbackBundle** to the `service.yml` of your project. 
**twig.extension.callback** service will load automatically.

Usage
=====

```
callback(route = null, parameters = [])
```
Returns current URI removing `callback` from query string.
Optionally, returns the absolute URL (with scheme and host) for the given route with given parameters, `callback` will be ignored as well.

|           | route  (optional) | parameters (optional)   |
| :-------- | :------------     | :---------------------- |
| type      | string            | array                   |
| default   | current uri       | []                      |

Inside your views:

By default callback will be defined on user's current page. 
```twig
    <a href="{{ path('app_foo_bar', { 'callback': callback() }) }}">
        Your Foo Bar link here
    </a>
```

But you can optionally redirect user to any route.
```twig
    <a href="{{ path('app_delete_foo', { 'callback': callback('app_trash_bin', { 'slug': 'foo' } ) }) }}">
        Your Foo Bar link here
    </a>
```
Callback function accepts route name and parameters for desired route.

Inside your action method:
```php
    public function foobarAction(Request $request)
    {
        ...
        // User is redirected to referrer page
        return $this->redirect($request->get('callback'));
        ...
    }
```

Tests
=====

**TangoMan CallbackBundle** provides Makefile script to perform unit tests, in order to fit in your continuous integration workflow.

Enter following command to install required dependencies and execute unit tests:

```bash
$ make tests
```

On windows machine you will need to install [cygwin](http://www.cygwin.com/) or [GnuWin make](http://gnuwin32.sourceforge.net/packages/make.htm) first to execute make script.

If you have XDebug installed, you can generate code coverage report with:
```bash
$ make coverage
```

Note
====

[![Build Status](https://travis-ci.org/TangoMan75/CallbackBundle.svg?branch=master)](https://travis-ci.org/TangoMan75/CallbackBundle) 
If you find any bug please report here : [Issues](https://github.com/TangoMan75/CallbackBundle/issues/new)

License
=======

Copyright (c) 2018 Matthias Morin

[![License][license-MIT]][license-url]
Distributed under the MIT license.

If you like **TangoMan CallbackBundle** please star!
And follow me on GitHub: [TangoMan75](https://github.com/TangoMan75)
... And check my other cool projects.

[Matthias Morin | LinkedIn](https://www.linkedin.com/in/morinmatthias)

[license-MIT]: https://img.shields.io/badge/Licence-MIT-green.svg
[license-url]: LICENSE
