<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Session\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @deprecated This is service provider for BC. Session should be added for session client before every other service that can use it. E.g. `WebProfilerServiceProvider`.
 *
 * @method \Spryker\Yves\Session\SessionConfig getConfig()
 * @method \Spryker\Yves\Session\SessionFactory getFactory()
 * @method \Spryker\Client\Session\SessionClientInterface getClient()
 */
class SessionClientServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    protected const SERVICE_SESSION = 'session';

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
        $sessionClient = $this->getClient();
        $sessionClient->setContainer($this->getSessionService($app));
    }

    /**
     * @param \Silex\Application $container
     *
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected function getSessionService(Application $container): SessionInterface
    {
        return $container->get(static::SERVICE_SESSION);
    }
}
