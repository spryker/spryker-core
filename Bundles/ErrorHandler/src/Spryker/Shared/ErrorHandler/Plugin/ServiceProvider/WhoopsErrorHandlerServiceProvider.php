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
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class WhoopsErrorHandlerServiceProvider implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $userPath = $this->getUserPath();

        $whoops = new Run();
        $handler = new PrettyPageHandler();
        $handler->setEditor(function ($file, $line) use ($userPath) {
            $serverPath = '/data/shop/development/current';

            return sprintf('phpstorm://open?file=%s&line=%s', str_replace($serverPath, $userPath, $file), $line);
        });
        $whoops->pushHandler($handler);
        $whoops->register();
    }

    /**
     * @throws \Exception
     *
     * @return mixed
     */
    protected function getUserPath()
    {
        $userPath = Config::get(ErrorHandlerConstants::USER_BASE_PATH, '');
        if (!$userPath) {
            throw new \Exception('Could not find user path for replacement in config. You need to add "ErrorHandlerConstants::USER_BASE_PATH" with a path to the files on your machine.');
        }

        return $userPath;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

}
