<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriter;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductConcreteStorageWriter;
use Spryker\Zed\PriceProductStorage\PriceProductStorageDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 */
class PriceProductStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriterInterface
     */
    public function createPriceProductAbstractStorageWriter()
    {
        return new PriceProductAbstractStorageWriter(
            $this->getPriceProductFacade(),
            $this->getQueryContainer(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductConcreteStorageWriterInterface
     */
    public function createPriceProductConcreteStorageWriter()
    {
        return new PriceProductConcreteStorageWriter(
            $this->getPriceProductFacade(),
            $this->getQueryContainer(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Dependency\Facade\PriceProductStorageToPriceProductFacadeInterface
     */
    protected function getPriceProductFacade()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::STORE);
    }
}
