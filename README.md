![GH language](https://img.shields.io/github/languages/top/TangoMan75/CallbackBundle)
[![GH release](https://img.shields.io/github/v/release/TangoMan75/CallbackBundle)](https://github.com/TangoMan75/CallbackBundle/releases)
![GH license](https://img.shields.io/github/license/TangoMan75/CallbackBundle)
![GH stars](https://img.shields.io/github/stars/TangoMan75/CallbackBundle)
[![PHP CI](https://github.com/TangoMan75/CallbackBundle/workflows/PHP%20CI/badge.svg)](https://github.com/TangoMan75/CallbackBundle/actions/workflows/php.yml)
![Visitors](https://api.visitorbadge.io/api/visitors?path=https%3A%2F%2Fgithub.com%2FTangoMan75%2FCallbackBundle&labelColor=%23697689&countColor=%2337d67a&style=flat)

TangoMan Callback Twig Extension Bundle
=======================================

**TangoMan Callback Twig Extension Bundle** provides simple callback function for twig which avoids unnecessary callbacks to stack up indefinitely in users query string.

🧩 Purpose of the `Callback` Class
----------------------------------

This class defines a Twig extension that introduces a custom function called `callback`. Its main job is to **generate clean URLs** by **removing `callback` parameters from query strings**, which can help avoid issues where multiple callbacks get appended indefinitely and make sure users query parameters are not lost in the process.

🔧 `callbackFunction()`
-----------------------

```php
public function callbackFunction($route = null, $parameters = [])
```

- **Behavior**:
  - If no route is provided -> use the current request URI.
  - If a route is specified -> generate a full URL using Symfony's router.
- **Core logic**:
  - Parses the URL.
  - Removes the `callback` parameter from the query string.
  - Reconstructs and returns the cleaned URL.

✨ Real-Life Use Case
---------------------

Imagine you're building a redirect system where callbacks are passed through URLs. If multiple redirects occur (e.g., login flow, third-party auth, or form submissions), you might get URLs like this:

```
https://example.com/page?callback=https://another.com?callback=...
```

This class avoids that endless loop by **stripping the `callback`** query parameter from the final URL so it stays nice and tidy.

📦 Installation
===============

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

You don't have to add **TangoMan Callback Twig Extension Bundle** to the `service.yml` of your project. 
**twig.extension.callback** service will load automatically.

🛠️ Usage
=======

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

✅ Tests
========

**TangoMan Callback Twig Extension Bundle** provides Makefile script to perform unit tests, in order to fit in your continuous integration workflow.

Enter following command to install required dependencies and execute unit tests:

```bash
$ make tests
```

On windows machine you will need to install [cygwin](http://www.cygwin.com/) or [GnuWin make](http://gnuwin32.sourceforge.net/packages/make.htm) first to execute make script.

If you have XDebug installed, you can generate code coverage report with:
```bash
$ make coverage
```

📝 Note
=======

[![Build Status](https://travis-ci.org/TangoMan75/CallbackBundle.svg?branch=master)](https://travis-ci.org/TangoMan75/CallbackBundle) 
If you find any bug please report here : [Issues](https://github.com/TangoMan75/CallbackBundle/issues/new)

🤝 Contributing
---------------

Thank you for your interest in contributing to **TangoMan Callback Twig Extension Bundle**.

Please review the [code of conduct](./CODE_OF_CONDUCT.md) and [contribution guidelines](./CONTRIBUTING.md) before starting to work on any features.

If you want to open an issue, please check first if it was not [reported already](https://github.com/TangoMan75/CallBackBundle/issues) before creating a new one.

📜 License
----------

Copyrights (c) 2025 &quot;Matthias Morin&quot; &lt;mat@tangoman.io&gt;

[![License](https://img.shields.io/badge/Licence-MIT-green.svg)](LICENSE)
Distributed under the MIT license.

If you like **TangoMan Callback Twig Extension Bundle** please star, follow or tweet:

[![GitHub stars](https://img.shields.io/github/stars/TangoMan75/CallBackBundle?style=social)](https://github.com/TangoMan75/CallBackBundle/stargazers)
[![GitHub followers](https://img.shields.io/github/followers/TangoMan75?style=social)](https://github.com/TangoMan75)
[![Twitter](https://img.shields.io/twitter/url?style=social&url=https%3A%2F%2Fgithub.com%2FTangoMan75%2FCallBackBundle)](https://twitter.com/intent/tweet?text=Wow:&url=https%3A%2F%2Fgithub.com%2FTangoMan75%2FCallBackBundle)

... And check my other cool projects.

👋 Let's Build Your Next Project Together !
-------------------------------------------

Looking for an experienced Full-Stack Partner ?

Clean code. Clear communication.

From first sketch to final launch, I've got your back.

[![tangoman.io](https://img.shields.io/badge/✉️%20Get%20in%20touch%20now%20!-FD9400?style=for-the-badge)](https://tangoman.io)
