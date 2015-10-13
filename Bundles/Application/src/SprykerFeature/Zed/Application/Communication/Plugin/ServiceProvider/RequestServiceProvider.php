<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ServiceProviderInterface;

class RequestServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    const BUNDLE = 'module';
    const CONTROLLER = 'controller';
    const ACTION = 'action';

    const DEFAULT_BUNDLE = 'application';
    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = 'index';

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

        $requiredParameters = [
            self::BUNDLE,
            self::CONTROLLER,
            self::ACTION,
        ];

        foreach ($requiredParameters as $parameter) {
            if (!$request->attributes->has($parameter)) {
                throw new \InvalidArgumentException(sprintf('Required parameter --%s is missing!', $parameter));
            }
        }
    }

    /**
     * @param Request $request
     */
    protected function parseRequestData(Request $request)
    {
        $requestUriWithoutParameters = strtok($request->server->get('REQUEST_URI'), '?');
        $requestUriWithoutParameters = trim($requestUriWithoutParameters, '/');
        $requestUriWithoutParameters = str_replace('//', '/', $requestUriWithoutParameters);
        $requestUriParts = explode('/', $requestUriWithoutParameters);

        if (count($requestUriParts) < 3) {
            $request->attributes->set(self::ACTION, self::DEFAULT_ACTION);
        } else {
            $request->attributes->set(self::ACTION, $requestUriParts[2]);
        }
        if (count($requestUriParts) < 2) {
            $request->attributes->set(self::CONTROLLER, self::DEFAULT_CONTROLLER);
        } else {
            $request->attributes->set(self::CONTROLLER, $requestUriParts[1]);
        }
        if (count($requestUriParts) < 1 || empty($requestUriParts[0])) {
            $request->attributes->set(self::BUNDLE, self::DEFAULT_BUNDLE);
        } else {
            $request->attributes->set(self::BUNDLE, $requestUriParts[0]);
        }
    }

}
