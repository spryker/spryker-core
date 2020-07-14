<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductBundleStorage\Business\Writer\ProductBundleStorageWriter;
use Spryker\Zed\ProductBundleStorage\Business\Writer\ProductBundleStorageWriterInterface;
use Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleStorage\ProductBundleStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductBundleStorage\ProductBundleStorageConfig getConfig()
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageRepositoryInterface getRepository()
 */
class ProductBundleStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductBundleStorage\Business\Writer\ProductBundleStorageWriterInterface
     */
    public function createProductBundleStorageWriter(): ProductBundleStorageWriterInterface
    {
        return new ProductBundleStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getProductBundleFacade(),
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductBundleStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductBundleStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface
     */
    public function getProductBundleFacade(): ProductBundleStorageToProductBundleFacadeInterface
    {
        return $this->getProvidedDependency(ProductBundleStorageDependencyProvider::FACADE_PRODUCT_BUNDLE);
    }
}
