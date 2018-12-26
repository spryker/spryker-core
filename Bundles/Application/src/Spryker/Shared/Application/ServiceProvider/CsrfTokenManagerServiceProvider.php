<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\ContainerGlobals;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

class CsrfTokenManagerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $containerGlobals = new ContainerGlobals();

        $containerGlobals[ApplicationConstants::CSRF_TOKEN_MANAGER] = $containerGlobals->share(function () use ($app) {
            $storage = isset($app['session']) ? new SessionTokenStorage($app['session']) : new NativeSessionTokenStorage();

            return new CsrfTokenManager(null, $storage);
        });
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
