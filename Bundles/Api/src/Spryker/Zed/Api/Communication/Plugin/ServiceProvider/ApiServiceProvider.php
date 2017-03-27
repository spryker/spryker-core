<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin\ServiceProvider;

use RuntimeException;
use Silex\Application;
use Silex\ServiceControllerResolver;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Api\Business\Model\Router\ApiRouter;

class ApiServiceProvider implements ServiceProviderInterface
{

    /**
     * @throws \RuntimeException
     *
     * @return void
     */
    public function register(Application $app)
    {
        if (!($app['resolver'] instanceof ServiceControllerResolver)) {
            throw new RuntimeException('Register ServiceControllerServiceProvider first.');
        }

        /*
        foreach (array('cget', 'post', 'get', 'put', 'patch', 'delete') as $method) {
            $app['rest.methods.'.$method] = $method;
        }

        $app['rest'] = $app->share(function ($app) {
            return new RestService($app);
        });
        */

        $app->addRouter(new ApiRouter($app));
    }

    /**
     * @return void
     */
    public function boot(Application $app)
    {
    }

}
