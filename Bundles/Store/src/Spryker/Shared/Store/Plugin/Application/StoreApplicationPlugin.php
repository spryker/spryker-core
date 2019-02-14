<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Store\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\Config\Application\Environment as ApplicationEnvironment;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\HttpFoundation\Request;

class StoreApplicationPlugin implements ApplicationPluginInterface
{
    public const LOCALE = 'locale';
    public const STORE = 'store';
    public const REQUEST_URI = 'REQUEST_URI';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addLocale($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addLocale(ContainerInterface $container): ContainerInterface
    {
        $store = Store::getInstance();
        $store->setCurrentLocale(current($store->getLocales()));
        $container->set(static::LOCALE, function () use ($store) {
            return $store->getCurrentLocale();
        });

        $requestUri = $this->getRequestUri();

        if ($requestUri) {
            $pathElements = explode('/', trim($requestUri, '/'));
            $identifier = $pathElements[0];
            if ($identifier !== false && array_key_exists($identifier, $store->getLocales())) {
                $store->setCurrentLocale($store->getLocales()[$identifier]);
                $container->set(static::LOCALE, function () use ($store) {
                    return $store->getCurrentLocale();
                });
                ApplicationEnvironment::initializeLocale($store->getCurrentLocale());
            }
        }

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addStore(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::STORE, function () {
            return Store::getInstance()->getStoreName();
        });

        return $container;
    }

    /**
     * @return string
     */
    protected function getRequestUri(): string
    {
        $requestUri = Request::createFromGlobals()
            ->server->get(self::REQUEST_URI);

        return $requestUri;
    }
}
