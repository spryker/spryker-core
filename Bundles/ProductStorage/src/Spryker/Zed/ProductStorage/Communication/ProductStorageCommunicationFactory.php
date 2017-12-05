<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductStorage\Communication\Helper\AttributeMapHelper;
use Spryker\Zed\ProductStorage\ProductStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductStorage\ProductStorageConfig getConfig()
 */
class ProductStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\ProductStorage\Dependency\Service\ProductStorageToUtilSynchronizationInterface
     */
    public function getUtilSynchronization()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::SERVICE_UTIL_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductBridge
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductStorage\Communication\Helper\AttributeMapHelperInterface
     */
    public function createAttributeMapHelper()
    {
        return new AttributeMapHelper(
            $this->getProductFacade(),
            $this->getQueryContainer()
        );
    }

}
