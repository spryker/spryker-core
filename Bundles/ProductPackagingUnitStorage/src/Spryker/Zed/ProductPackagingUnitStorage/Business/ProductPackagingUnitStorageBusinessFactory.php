<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\PriceProductAbstractStorageWriter;
use Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductAbstractPackagingStorageWriter;
use Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\ProductPackagingUnitStorageQueryContainerInterface getQueryContainer()
 */
class ProductPackagingUnitStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\ProductAbstractPackagingStorageWriterInterface
     */
    public function createProductAbstractPackagingStorageWriter()
    {
        return new ProductAbstractPackagingStorageWriter(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface
     */
    protected function getPriceProductFacade()
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(ProductPackagingUnitStorageDependencyProvider::FACADE_STORE);
    }
}
