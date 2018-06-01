<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\PriceProductAbstractStorageWriter;
use Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\PriceProductConcreteStorageWriter;
use Spryker\Zed\ProductPackagingUnitStorage\PriceProductStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\PriceProductStorageConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 */
class PriceProductStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\PriceProductAbstractStorageWriterInterface
     */
    public function createPriceProductAbstractStorageWriter()
    {
        return new PriceProductAbstractStorageWriter(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Business\Storage\PriceProductConcreteStorageWriterInterface
     */
    public function createPriceProductConcreteStorageWriter()
    {
        return new PriceProductConcreteStorageWriter(
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
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\PriceProductStorageToStoreFacadeInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::FACADE_STORE);
    }
}
