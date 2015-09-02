<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ServiceProviderInterface;

class RequestServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app->before(function (Request $request) {
            if ($request->server->get('argv', false)) {
                $this->parseCliRequestData($request);
            } else {
                $this->parseRequestData($request);
            }
        }, Application::EARLY_EVENT);
    }

    /**
     * @param Request $request
     */
    protected function parseCliRequestData(Request $request)
    {
        foreach ($request->server->get('argv') as $argument) {
            preg_match_all('/--(.*)=(.*)/', $argument, $matches);

            if ($matches[0]) {
                $key = $matches[1][0];
                $value = $matches[2][0];
                $request->attributes->set($key, $value);
            }
        }

        if (is_null($request->attributes->has('module')) || is_null($request->attributes->has('controller')) || is_null($request->attributes->has('action'))) {
            throw new \InvalidArgumentException('One of the required parameter (--module, --controller, --action) is missing!');
        }
    }

    /**
     * @param Request $request
     */
    protected function parseRequestData(Request $request)
    {
        $requestUriWithoutParameters = strtok($request->server->get('REQUEST_URI'),'?');
        $requestUriWithoutParameters = trim($requestUriWithoutParameters, '/');
        $requestUriWithoutParameters = str_replace('//', '/', $requestUriWithoutParameters);
        $requestUriParts = explode('/', $requestUriWithoutParameters);

        if (count($requestUriParts) < 3) {
            $request->attributes->set('action', 'index');
        } else {
            $request->attributes->set('action', $requestUriParts[2]);
        }
        if (count($requestUriParts) < 2) {
            $request->attributes->set('controller', 'index');
        } else {
            $request->attributes->set('controller', $requestUriParts[1]);
        }
        if (count($requestUriParts) < 1 || empty($requestUriParts[0])) {
            $request->attributes->set('module', 'application');
        } else {
            $request->attributes->set('module', $requestUriParts[0]);
        }
    }

}
