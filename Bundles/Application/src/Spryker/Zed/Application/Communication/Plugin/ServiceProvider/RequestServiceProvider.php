<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use InvalidArgumentException;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use {@link \Spryker\Zed\Router\Communication\Plugin\EventDispatcher\RequestAttributesEventDispatcherPlugin} instead.
 *
 * The Router Module will take care about it from now on.
 *
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class RequestServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @var string
     */
    public const BUNDLE = 'module';

    /**
     * @var string
     */
    public const CONTROLLER = 'controller';

    /**
     * @var string
     */
    public const ACTION = 'action';

    /**
     * @var string
     */
    public const DEFAULT_BUNDLE = 'application';

    /**
     * @var string
     */
    public const DEFAULT_CONTROLLER = 'index';

    /**
     * @var string
     */
    public const DEFAULT_ACTION = 'index';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app->before(function (Request $request) {
            if ($this->isCli() && $request->server->get('argv', false)) {
                $this->parseCliRequestData($request);
            } else {
                $this->parseRequestData($request);
            }
        }, Application::EARLY_EVENT);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function parseCliRequestData(Request $request)
    {
        foreach ($request->server->get('argv') as $argument) {
            preg_match_all('/^--([\w-]*)=([\w-]*)$/', $argument, $matches);

            if ($matches[0]) {
                $key = $matches[1][0];
                $value = $matches[2][0];
                $request->attributes->set($key, $value);
            }
        }

        $requiredParameters = [
            static::BUNDLE,
            static::CONTROLLER,
            static::ACTION,
        ];

        foreach ($requiredParameters as $parameter) {
            if (!$request->attributes->has($parameter)) {
                throw new InvalidArgumentException(sprintf('Required parameter --%s is missing!', $parameter));
            }
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function parseRequestData(Request $request)
    {
        /** @var string $requestUriWithoutParameters */
        $requestUriWithoutParameters = strtok($request->server->get('REQUEST_URI'), '?');
        $requestUriWithoutParameters = trim($requestUriWithoutParameters, '/');
        $requestUriWithoutParameters = str_replace('//', '/', $requestUriWithoutParameters);
        $requestUriParts = explode('/', $requestUriWithoutParameters);

        if (count($requestUriParts) < 3) {
            $request->attributes->set(static::ACTION, static::DEFAULT_ACTION);
        } else {
            $request->attributes->set(static::ACTION, $requestUriParts[2]);
        }
        if (count($requestUriParts) < 2) {
            $request->attributes->set(static::CONTROLLER, static::DEFAULT_CONTROLLER);
        } else {
            $request->attributes->set(static::CONTROLLER, $requestUriParts[1]);
        }
        if (count($requestUriParts) < 1 || empty($requestUriParts[0])) {
            $request->attributes->set(static::BUNDLE, static::DEFAULT_BUNDLE);
        } else {
            $request->attributes->set(static::BUNDLE, $requestUriParts[0]);
        }
    }

    /**
     * @return bool
     */
    protected function isCli()
    {
        return PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg';
    }
}
