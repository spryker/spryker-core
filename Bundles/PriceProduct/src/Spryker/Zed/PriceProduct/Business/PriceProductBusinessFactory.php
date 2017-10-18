<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PriceProduct\Business\Internal\Install;
use Spryker\Zed\PriceProduct\Business\Model\BulkWriter;
use Spryker\Zed\PriceProduct\Business\Model\Reader;
use Spryker\Zed\PriceProduct\Business\Model\Writer;
use Spryker\Zed\PriceProduct\PriceProductDependencyProvider;

/**
 * @method \Spryker\Zed\PriceProduct\PriceProductConfig getConfig()
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainer getQueryContainer()
 */
class PriceProductBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\ReaderInterface
     */
    public function createReaderModel()
    {
        return new Reader(
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getConfig(),
            $this->getCurrencyFacade(),
            $this->getPriceFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\WriterInterface
     */
    public function createWriterModel()
    {
        return new Writer(
            $this->getQueryContainer(),
            $this->createReaderModel(),
            $this->getTouchFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Model\BulkWriterInterface
     */
    public function createBulkWriterModel()
    {
        return new BulkWriter(
            $this->getQueryContainer(),
            $this->createReaderModel(),
            $this->getTouchFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\Internal\InstallInterface
     */
    public function createInstaller()
    {
        return new Install($this->createWriterModel(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::FACADE_STORE);
    }

}
