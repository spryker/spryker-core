<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Tax\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Tax\Business\Model\OrderAmountAggregator\ItemTax;
use Spryker\Zed\Tax\Business\Model\OrderAmountAggregator\OrderTaxAmount;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface;
use Spryker\Zed\Tax\Business\Model\TaxCalculation;
use Spryker\Zed\Tax\TaxConfig;
use Spryker\Zed\Tax\Business\Model\TaxReaderInterface;
use Spryker\Zed\Tax\Business\Model\TaxWriterInterface;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelper;
use Spryker\Zed\Tax\Persistence\TaxQueryContainer;
use Spryker\Zed\Tax\Business\Model\TaxWriter;
use Spryker\Zed\Tax\Business\Model\TaxReader;
use Spryker\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface;

/**
 * @method TaxConfig getConfig()
 * @method TaxQueryContainer getQueryContainer()
 */
class TaxBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return TaxReaderInterface
     */
    public function createReaderModel()
    {
        return new TaxReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return TaxWriterInterface
     */
    public function createWriterModel()
    {
        return new TaxWriter(
            $this->getQueryContainer(),
            $this->getTaxChangePlugins()
        );
    }

    /**
     * @return TaxChangePluginInterface[]
     */
    public function getTaxChangePlugins()
    {
        return [];
    }

    /**
     * @return TaxCalculation
     */
    public function createTaxCalculator()
    {
        return new TaxCalculation($this->createPriceCalculationHelper());
    }

    /**
     * @return PriceCalculationHelperInterface
     */
    public function createPriceCalculationHelper()
    {
        return new PriceCalculationHelper();
    }

    /**
     * @return ItemTax
     */
    public function createOrderItemTaxAmountAggregator()
    {
        return new ItemTax($this->createPriceCalculationHelper());
    }

    /**
     * @return OrderTaxAmount
     */
    public function createOrderTaxAmountAggregator()
    {
        return new OrderTaxAmount($this->createPriceCalculationHelper());
    }
}
