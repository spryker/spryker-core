<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage;

use Exception;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\ClassResolver\Client\ClientNotFoundException;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToGlossaryStorageClientBridge;
use Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToProductImageClientBridge;
use Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToStorageBridge;
use Spryker\Client\ProductImageStorage\Dependency\Service\ProductImageStorageToSynchronizationServiceBridge;

/**
 * @method \Spryker\Client\ProductImageStorage\ProductImageStorageConfig getConfig()
 */
class ProductImageStorageDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @var string
     */
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @var string
     */
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_IMAGE = 'CLIENT_PRODUCT_IMAGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addGlossaryClient($container);
        $container = $this->addProductImageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new ProductImageStorageToStorageBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new ProductImageStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addGlossaryClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            $module = 'glossaryStorage';
            try {
                return new ProductImageStorageToGlossaryStorageClientBridge($container->getLocator()->$module()->client());
            } catch (ClientNotFoundException) {
                throw new Exception('Missing "spryker/glossary-storage" module.');
            }
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductImageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_IMAGE, function (Container $container) {
            return new ProductImageStorageToProductImageClientBridge($container->getLocator()->productImage()->client());
        });

        return $container;
    }
}
