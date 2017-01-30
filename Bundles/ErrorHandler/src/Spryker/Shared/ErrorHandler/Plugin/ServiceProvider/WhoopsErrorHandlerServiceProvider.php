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
        $userPath = Config::get(ErrorHandlerConstants::USER_BASE_PATH, '');

        $whoops = new Run();
        $handler = new PrettyPageHandler();
        if ($userPath) {
            $handler->setEditor(function ($file, $line) use ($userPath) {
                $serverPath = '/data/shop/development/current';
                $file = str_replace($serverPath, $userPath, $file);
                $url = sprintf('phpstorm://open?file=%s&line=%s', $file, $line);

                if (!Config::get(ErrorHandlerConstants::AS_AJAX, false)) {
                    return $url;
                }

                return [
                    'url' => $url,
                    'ajax' => true,
                ];
            });
        }
        $whoops->pushHandler($handler);
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

}
