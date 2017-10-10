<?php

namespace TangoMan\CallbackBundle\TwigExtension;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Callback
 * Avoids multiple callbacks appending indefinitely.
 *
 * @author  Matthias Morin <matthias.morin@gmail.com>
 * @package AppBundle\TwigExtension
 */
class Callback extends \Twig_Extension
{
    /**
     * @var RequestStack
     */
    protected $request;

    /**
     * Callback constructor.
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
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
     * @param string $uri
     *
     * @return string
     */
    public function callbackFunction($uri = null)
    {
        if ($uri === null) {
            $uri = $this->request->getUri();
        }

        $result = parse_url($uri);

        // When url contains query string
        if (isset($result['query'])) {

            parse_str($result['query'], $query);

            // Remove callback from query
            $query = array_diff_key($query, ['callback' => null]);
        } else {
            // Return unchanged url
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
