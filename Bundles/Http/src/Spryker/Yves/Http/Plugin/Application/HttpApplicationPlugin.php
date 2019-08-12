<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Http\Plugin\Application;

use ArrayObject;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\Http\HttpConfig getConfig()
 * @method \Spryker\Yves\Http\HttpFactory getFactory()
 */
class HttpApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    public const COOKIES_SERVICE = 'cookies';

    /**
     * {@inheritdoc}
     * - Sets trusted proxies and host.
     * - Sets `cookies` service identifier.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $this->setTrustedProxies();
        $this->setTrustedHosts();

        $container = $this->addCookie($container);

        return $container;
    }

    /**
     * @return void
     */
    protected function setTrustedProxies(): void
    {
        Request::setTrustedProxies($this->getConfig()->getTrustedProxies(), $this->getConfig()->getTrustedHeaderSet());
    }

    /**
     * @return void
     */
    protected function setTrustedHosts(): void
    {
        Request::setTrustedHosts($this->getConfig()->getTrustedHosts());
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addCookie(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::COOKIES_SERVICE, function () {
            return new ArrayObject();
        });

        return $container;
    }
}
