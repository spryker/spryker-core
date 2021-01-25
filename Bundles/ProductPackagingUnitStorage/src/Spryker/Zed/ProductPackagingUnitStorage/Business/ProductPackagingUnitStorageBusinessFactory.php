<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingUnitStorageWriter;
use Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingUnitStorageWriterInterface;
use Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface;
use Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface getEntityManager()
 */
class ProductPackagingUnitStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingUnitStorageWriterInterface
     */
    public function createProductPackagingUnitStorageWriter(): ProductPackagingUnitStorageWriterInterface
    {
        return new ProductPackagingUnitStorageWriter(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface
     */
    public function getProductPackagingUnitFacade(): ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::FACADE_PRODUCT_PACKAGING_UNIT);
    }
}
