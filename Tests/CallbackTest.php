<?php
/**
 * Copyright (c) 2018 Matthias Morin <matthias.morin@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;
use TangoMan\CallbackBundle\TwigExtension\Callback;

/**
 * Class CallbackTest
 *
 * @author  Matthias Morin <matthias.morin@gmail.com>
 * @package TangoMan\CallbackBundle\Tests
 */
class CallbackTest extends TestCase
{

    public function testCallback()
    {
        // Test uri unaltered through request
        $uri    = 'https://www.tangoman.com/admin/posts';
        $result = $this->requestTest($uri);
        $this->assertEquals($uri, $result);

        // Test query unaltered through request
        $uri
                = 'https://www.tangoman.com/admin/posts/?page=1&order=title&way=DESC';
        $result = $this->requestTest($uri);
        $this->assertEquals($uri, $result);

        // Test callback removed from query through request
        $uri
                = 'https://www.tangoman.com/admin/posts/edit/91?callback=http%3A//www.tangoman.com/admin/posts/%3Forder%3Dtitle%26page%3D1%26way%3DDESC';
        $result = $this->requestTest($uri);
        $this->assertEquals(
            'https://www.tangoman.com/admin/posts/edit/91',
            $result
        );

        // Test uri unaltered through router
        $uri    = 'https://www.tangoman.com/admin/posts';
        $result = $this->routerTest($uri, 'app_foo_bar', ['slug' => 'foo']);
        $this->assertEquals($uri, $result);

        // Test query unaltered through router
        $uri
                = 'https://www.tangoman.com/admin/posts/?page=1&order=title&way=DESC';
        $result = $this->routerTest($uri, 'app_foo_bar', ['slug' => 'foo']);
        $this->assertEquals($uri, $result);

        // Test callback removed from query through router
        $uri
                = 'https://www.tangoman.com/admin/posts/edit/91?callback=http%3A//www.tangoman.com/admin/posts/%3Forder%3Dtitle%26page%3D1%26way%3DDESC';
        $result = $this->routerTest($uri, 'app_foo_bar', ['slug' => 'foo']);
        $this->assertEquals(
            'https://www.tangoman.com/admin/posts/edit/91',
            $result
        );
    }

    private function requestTest($uri = null, $route = null, $parameters = [])
    {
        $request = $this->createConfiguredMock(
            Request::class,
            ['getUri' => $uri]
        );
        $request->expects($this->once())->method('getUri');

        $requestStack = $this->createConfiguredMock(
            RequestStack::class,
            ['getCurrentRequest' => $request]
        );
        $requestStack->expects($this->once())->method('getCurrentRequest');

        $router = $this->createMock(Router::class);
        $router->expects($this->never())->method('generate');

        $callback = new Callback($requestStack, $router);

        return $callback->callbackFunction($route, $parameters);
    }

    private function routerTest($uri = null, $route = null, $parameters = [])
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->never())->method('getUri');

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->expects($this->once())->method('getCurrentRequest');

        $router = $this->createConfiguredMock(
            Router::class,
            ['generate' => $uri]
        );
        $router->expects($this->once())->method('generate');

        $callback = new Callback($requestStack, $router);

        return $callback->callbackFunction($route, $parameters);
    }
}