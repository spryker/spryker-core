<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Plugin\GlueStorefrontApiApplication;

use Exception;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;

/**
 * @method \Spryker\Glue\StoresApi\StoresApiFactory getFactory()
 */
class StoreApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @var string
     */
    protected const HEADER_STORE_NAME = 'Store';

    /**
     * @var string
     */
    protected const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    protected const PARAMETER_STORE_NAME = '_store';

    /**
     * {@inheritDoc}
     * - Gets store name from the Request header or from the Request parameter.
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $storeName = $this->resolveStoreName();

        if ($storeName === '') {
            return $container;
        }

        $container->set(static::SERVICE_STORE, function (ContainerInterface $container) use ($storeName) {
            return $storeName;
        });

        return $container;
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    protected function resolveStoreName(): string
    {
        $storeName = $this->getStoreName();
        $storeNames = $this->getFactory()->getStoreStorageClient()->getStoreNames();
        if ($storeName !== '') {
            return $storeName;
        }

        if (defined('APPLICATION_STORE')) {
            return APPLICATION_STORE;
        }

        $defaultStoreName = current($storeNames);

        if (!$defaultStoreName) {
            throw new Exception('Cannot resolve store.');
        }

        return $defaultStoreName;
    }

    /**
     * @return string
     */
    protected function getStoreName(): string
    {
        $request = $this->getFactory()->createRequest();
        $storeName = '';

        if ($request->headers->get(static::HEADER_STORE_NAME) !== null) {
            $storeName = (string)$request->headers->get(static::HEADER_STORE_NAME);
        }

        if ($request->query->get(static::PARAMETER_STORE_NAME) !== null) {
            $storeName = (string)$request->query->get(static::PARAMETER_STORE_NAME);
        }

        return $storeName;
    }
}
