<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingStorageReader;
use Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingStorageReaderInterface;
use Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingStorageWriter;
use Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingStorageWriterInterface;
use Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface;
use Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageQueryContainerInterface getQueryContainer()
 */
class ProductPackagingUnitStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingStorageReaderInterface
     */
    public function createProductPackagingStorageReader(): ProductPackagingStorageReaderInterface
    {
        return new ProductPackagingStorageReader(
            $this->getRepository(),
            $this->getProductPackagingUnitFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductPackagingStorageWriterInterface
     */
    public function createProductPackagingStorageWriter(): ProductPackagingStorageWriterInterface
    {
        return new ProductPackagingStorageWriter(
            $this->getEntityManager(),
            $this->createProductPackagingStorageReader()
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
