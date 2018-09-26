<?php
/**
 * Copyright (c) 2018 Matthias Morin <matthias.morin@gmail.com>
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
 * @author  Matthias Morin <matthias.morin@gmail.com>
 * @package TangoMan\CallbackBundle\Tests
 */
class CallbackTest extends TestCase
{

    /**
     *
     */
    public function testCallback()
    {
        $uris = [
            'https://www.example.com/admin/posts',
            'https://www.example.com/admin/posts/?page=1&order=title&way=DESC',
            'https://www.example.com/admin/posts/edit/91?callback=http%3A//www.tangoman.com/admin/posts/%3Forder%3Dtitle%26page%3D1%26way%3DDESC',
            'https://www.example.com/admin/posts/edit/91',
        ];

        // Test uri unaltered through request
        $result = $this->requestTest($uris[0]);
        $this->assertEquals($uris[0], $result);

        // Test query unaltered through request
        $result = $this->requestTest($uris[1]);
        $this->assertEquals($uris[1], $result);

        // Test callback removed from query through request
        $result = $this->requestTest($uris[2]);
        $this->assertEquals($uris[3], $result);

        // Test uri unaltered through router
        $result = $this->routerTest($uris[0], 'app_foo_bar', ['slug' => 'foo']);
        $this->assertEquals($uris[0], $result);

        // Test query unaltered through router
        $result = $this->routerTest($uris[1], 'app_foo_bar', ['slug' => 'foo']);
        $this->assertEquals($uris[1], $result);

        // Test callback removed from query through router
        $result = $this->routerTest($uris[2], 'app_foo_bar', ['slug' => 'foo']);
        $this->assertEquals($uris[3], $result);
    }

    /**
     * @param null  $uri
     * @param null  $route
     * @param array $parameters
     *
     * @return string
     */
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

    /**
     * @param null  $uri
     * @param null  $route
     * @param array $parameters
     *
     * @return string
     */
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