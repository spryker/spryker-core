<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundleStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientInterface;
use Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface;
use Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductBundleStorage\Expander\BundledProductExpander;
use Spryker\Client\ProductBundleStorage\Expander\BundledProductExpanderInterface;

class ProductBundleStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductBundleStorage\Expander\BundledProductExpanderInterface
     */
    public function createBundledProductExpander(): BundledProductExpanderInterface
    {
        return new BundledProductExpander(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\ProductBundleStorage\Dependency\Service\ProductBundleStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ProductBundleStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductBundleStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToStorageClientInterface
     */
    public function getStorageClient(): ProductBundleStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ProductBundleStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductBundleStorage\Dependency\Client\ProductBundleStorageToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductBundleStorageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductBundleStorageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
