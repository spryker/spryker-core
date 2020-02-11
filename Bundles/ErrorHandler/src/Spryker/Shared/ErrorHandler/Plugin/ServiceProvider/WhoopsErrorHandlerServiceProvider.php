<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Whoops\Handler\CallbackHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * @deprecated Use `\Spryker\Zed\ErrorHandler\Communication\Plugin\Application\ErrorHandlerApplicationPlugin` instead.
 * @deprecated Use `\Spryker\Yves\ErrorHandler\Plugin\Application\ErrorHandlerApplicationPlugin` instead.
 */
class WhoopsErrorHandlerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $whoops = new Run();
        $whoops->pushHandler($this->getPrettyPageHandler());
        $whoops->pushHandler($this->getErrorLoggerCallbackHandler());

        $whoops->register();
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @return \Whoops\Handler\PrettyPageHandler
     */
    protected function getPrettyPageHandler()
    {
        $userPath = Config::get(ErrorHandlerConstants::USER_BASE_PATH, '');
        $handler = new PrettyPageHandler();
        if ($userPath) {
            $handler->setEditor(function ($file, $line) use ($userPath) {
                $serverPath = Config::get(ErrorHandlerConstants::SERVER_BASE_PATH, '/data/shop/development/current');
                $file = str_replace($serverPath, $userPath, $file);

                $pattern = Config::get(ErrorHandlerConstants::PATTERN_IDE_LINK, 'phpstorm://open?file=%s&line=%s');
                $url = sprintf($pattern, $file, $line);

                if (!Config::get(ErrorHandlerConstants::AS_AJAX, false)) {
                    return $url;
                }

                return [
                    'url' => $url,
                    'ajax' => true,
                ];
            });
        }

        return $handler;
    }

    /**
     * @return \Whoops\Handler\CallbackHandler
     */
    protected function getErrorLoggerCallbackHandler()
    {
        return new CallbackHandler(function ($exception) {
            ErrorLogger::getInstance()->log($exception);
        });
    }
}
