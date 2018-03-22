<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Stock\Business\Model\Calculator;
use Spryker\Zed\Stock\Business\Model\Reader;
use Spryker\Zed\Stock\Business\Model\Writer;
use Spryker\Zed\Stock\Business\Transfer\StockProductTransferMapper;
use Spryker\Zed\Stock\StockDependencyProvider;

/**
 * @method \Spryker\Zed\Stock\StockConfig getConfig()
 * @method \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface getQueryContainer()
 */
class StockBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Stock\Business\Model\CalculatorInterface
     */
    public function createCalculatorModel()
    {
        return new Calculator(
            $this->createReaderModel()
        );
    }

    /**
     * @return \Spryker\Zed\Stock\Business\Model\ReaderInterface
     */
    public function createReaderModel()
    {
        return new Reader(
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->createStockProductTransferMapper(),
            $this->getConfig(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Stock\Business\Model\WriterInterface
     */
    public function createWriterModel()
    {
        return new Writer(
            $this->getQueryContainer(),
            $this->createReaderModel(),
            $this->getTouchFacade(),
            $this->getStockUpdateHandlerPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Stock\Business\Transfer\StockProductTransferMapperInterface
     */
    public function createStockProductTransferMapper()
    {
        return new StockProductTransferMapper();
    }

    /**
     * @return \Spryker\Zed\Stock\Dependency\Facade\StockToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(StockDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(StockDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Stock\Dependency\Plugin\StockUpdateHandlerPluginInterface[]
     */
    protected function getStockUpdateHandlerPlugins()
    {
        return $this->getProvidedDependency(StockDependencyProvider::PLUGINS_STOCK_UPDATE);
    }

    /**
     * @return \Spryker\Zed\Stock\Dependency\Facade\StockToStoreFacadeInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(StockDependencyProvider::FACADE_STORE);
    }
}
