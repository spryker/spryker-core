<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use `\Spryker\Yves\Router\Plugin\EventDispatcher\RouterSslRedirectEventDispatcherPlugin` instead.
 *
 * @method \Spryker\Yves\Application\ApplicationConfig getConfig()
 */
class SslServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->setTrustedProxies();
        $this->setTrustedHosts();
        $this->addProtocolCheck($app);
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
     * @return void
     */
    protected function setTrustedProxies()
    {
        Request::setTrustedProxies(
            $this->getConfig()->getTrustedProxies(),
            $this->getConfig()->getTrustedHeader()
        );
    }

    /**
     * @return void
     */
    protected function setTrustedHosts()
    {
        Request::setTrustedHosts($this->getConfig()->getTrustedHosts());
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function addProtocolCheck(Application $app)
    {
        if (!$this->getConfig()->isSslEnabled()) {
            return;
        }

        $app->before(
            function (Request $request) {
                if ($this->shouldBeSsl($request)) {
                    $fakeRequest = clone $request;
                    $fakeRequest->server->set('HTTPS', true);

                    return new RedirectResponse($fakeRequest->getUri(), 301);
                }

                return null;
            },
            255
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function shouldBeSsl(Request $request)
    {
        $requestIsSecure = $request->isSecure();
        $isSslExcludedResource = $this->isSslExcludedResource($request);

        return (!$requestIsSecure && !$isSslExcludedResource);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isSslExcludedResource(Request $request)
    {
        return in_array($request->getPathInfo(), $this->getConfig()->getSslExcludedResources());
    }
}
