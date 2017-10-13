<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Price\Business\Internal\Install;
use Spryker\Zed\Price\Business\Model\BulkWriter;
use Spryker\Zed\Price\Business\Model\Reader;
use Spryker\Zed\Price\Business\Model\Writer;
use Spryker\Zed\Price\PriceDependencyProvider;

/**
 * @method \Spryker\Zed\Price\PriceConfig getConfig()
 * @method \Spryker\Zed\Price\Persistence\PriceQueryContainer getQueryContainer()
 */
class PriceBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Price\Business\Model\ReaderInterface
     */
    public function createReaderModel()
    {
        return new Reader(
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getConfig(),
            $this->getCurrencyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Price\Business\Model\WriterInterface
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
     * @return \Spryker\Zed\Price\Business\Model\BulkWriterInterface
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
     * @return \Spryker\Zed\Price\Dependency\Facade\PriceToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(PriceDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\Price\Dependency\Facade\PriceToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(PriceDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Price\Dependency\Facade\PriceToCurrencyInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getProvidedDependency(PriceDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\Price\Business\Internal\InstallInterface
     */
    public function createInstaller()
    {
        $installer = new Install(
            $this->createWriterModel(),
            $this->getConfig()
        );

        return $installer;
    }

}
