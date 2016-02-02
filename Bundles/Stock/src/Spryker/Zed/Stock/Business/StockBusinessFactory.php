<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Business;

use Spryker\Zed\Stock\Business\Model\Writer;
use Spryker\Zed\Stock\Business\Model\Reader;
use Spryker\Zed\Stock\Business\Model\Calculator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Stock\StockConfig;
use Spryker\Zed\Stock\StockDependencyProvider;
use Spryker\Zed\Stock\Persistence\StockQueryContainer;

/**
 * @method StockConfig getConfig()
 * @method StockQueryContainer getQueryContainer()
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
            $this->getProductFacade()
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
            $this->getTouchFacade()
        );
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

}
