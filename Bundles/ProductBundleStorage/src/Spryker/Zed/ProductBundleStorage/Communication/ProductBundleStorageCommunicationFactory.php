<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleStorage\ProductBundleStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductBundleStorage\ProductBundleStorageConfig getConfig()
 * @method \Spryker\Zed\ProductBundleStorage\Business\ProductBundleStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageRepositoryInterface getRepository()
 */
class ProductBundleStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface
     */
    public function getProductBundleFacade(): ProductBundleStorageToProductBundleFacadeInterface
    {
        return $this->getProvidedDependency(ProductBundleStorageDependencyProvider::FACADE_PRODUCT_BUNDLE);
    }
}
