<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
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
        $isSecure = $request->isSecure();
        $isYvesRequest = $this->isYvesRequest($request);
        $isSslExcludedResource = $this->isSslExcludedResource($request);

        return (!$isSecure && !$isYvesRequest && !$isSslExcludedResource);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isYvesRequest(Request $request)
    {
        return (bool)$request->headers->get('X-Yves-Host');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isSslExcludedResource(Request $request)
    {
        return in_array($request->attributes->get('module') . '/' . $request->attributes->get('controller'), $this->getConfig()->getSslExcludedResources());
    }
}
