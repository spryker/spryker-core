<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 */
class BackofficeStoreApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @var string
     */
    protected const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    protected const PARAMETER_STORE_NAME = '_store';

    /**
     * @var string
     */
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * {@inheritDoc}
     * - Provides store service.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if (!$this->getFacade()->isDynamicStoreEnabled()) {
            return $container;
        }

        $storeName = $this->getRequest()->query->get(static::PARAMETER_STORE_NAME);

        if (!$storeName) {
            return $container;
        }

        $container->set(static::SERVICE_STORE, function (ContainerInterface $container) use ($storeName) {
            return (string)$storeName;
        });

        return $container;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(): Request
    {
        return Request::createFromGlobals();
    }
}
