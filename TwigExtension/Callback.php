<?php
/**
 * Copyright (c) 2018 Matthias Morin <matthias.morin@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TangoMan\CallbackBundle\TwigExtension;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;

/**
 * Class Callback
 * Avoids multiple callbacks appending indefinitely.
 *
 * @author  Matthias Morin <matthias.morin@gmail.com>
 * @package TangoMan\CallbackBundle\TwigExtension
 */
class Callback extends \Twig_Extension
{

    /**
     * @var RequestStack
     */
    protected $request;

    /**
     * @var Router
     */
    protected $router;

    /**
     * Callback constructor.
     */
    public function __construct(RequestStack $requestStack, Router $router)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->router  = $router;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'callback';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('callback', [$this, 'callbackFunction']),
        ];
    }

    /**
     * Removes callbacks from query
     *
     * @param string $route
     * @param array  $parameters
     *
     * @return string
     */
    public function callbackFunction($route = null, $parameters = [])
    {
        if ($route === null || ! is_string($route)) {
            // Gets URI from current request
            $uri = $this->request->getUri();
        } else {
            // Generates URI from '_route'
            $uri = $this->router->generate(
                $route,
                $parameters,
                Router::ABSOLUTE_URL
            );
        }

        $result = parse_url($uri);

        // When uri contains query string
        if (isset($result['query'])) {

            parse_str($result['query'], $query);

            // Remove callback from query
            $query = array_diff_key($query, ['callback' => null]);
        } else {
            // Return unchanged uri
            return $uri;
        }

        return $result['scheme'].'://'.
               (isset($result['user']) ? $result['user'] : '').
               (isset($result['pass']) ? ':'.$result['pass'].'@' : '').
               $result['host'].
               (isset($result['port']) ? ':'.$result['port'] : '').
               (isset($result['path']) ? $result['path'] : '').
               ($query != [] ? '?'.http_build_query($query) : '').
               (isset($result['fragment']) ? '#'.$result['fragment'] : '');
    }
}
