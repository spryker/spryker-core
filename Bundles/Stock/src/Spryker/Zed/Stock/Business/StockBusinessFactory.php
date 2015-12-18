<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Business;

use Spryker\Zed\Stock\Business\Model\Writer;
use Spryker\Zed\Stock\Business\Model\Reader;
use Spryker\Zed\Stock\Business\Model\Calculator;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Stock\Dependency\Facade\StockToProductInterface;
use Spryker\Zed\Stock\Business\Model\ReaderInterface;
use Spryker\Zed\Stock\Business\Model\WriterInterface;
use Spryker\Zed\Stock\Business\Model\CalculatorInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface;
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
     * @return CalculatorInterface
     */
    public function getCalculatorModel()
    {
        return new Calculator(
            $this->getReaderModel()
        );
    }

    /**
     * @return ReaderInterface
     */
    public function getReaderModel()
    {
        return new Reader(
            $this->getQueryContainer(),
            $this->getProductFacade()
        );
    }

    /**
     * @return WriterInterface
     */
    public function getWriterModel()
    {
        return new Writer(
            $this->getQueryContainer(),
            $this->getReaderModel(),
            $this->getTouchFacade()
        );
    }

    /**
     * @return StockToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(StockDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return StockToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(StockDependencyProvider::FACADE_TOUCH);
    }

}
