<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Tax\Business\Model\AccruedTaxCalculator;
use Spryker\Zed\Tax\Business\Model\Calculator\TaxAmountAfterCancellationCalculator;
use Spryker\Zed\Tax\Business\Model\Calculator\TaxAmountCalculator;
use Spryker\Zed\Tax\Business\Model\Calculator\TaxRateAverageAggregator;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelper;
use Spryker\Zed\Tax\Business\Model\TaxDefault;
use Spryker\Zed\Tax\Business\Model\TaxReader;
use Spryker\Zed\Tax\Business\Model\TaxWriter;
use Spryker\Zed\Tax\TaxDependencyProvider;

/**
 * @method \Spryker\Zed\Tax\TaxConfig getConfig()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Tax\Persistence\TaxRepositoryInterface getRepository()()
 */
class TaxBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Tax\Business\Model\TaxReaderInterface
     */
    public function createReaderModel()
    {
        return new TaxReader(
            $this->getQueryContainer(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\TaxWriterInterface
     */
    public function createWriterModel()
    {
        return new TaxWriter(
            $this->getQueryContainer(),
            $this->getRepository(),
            $this->getTaxChangePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\Calculator\TaxRateAverageAggregator|\Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface
     */
    public function createTaxRateAverageAggregationCalculator()
    {
        return new TaxRateAverageAggregator($this->createPriceCalculationHelper());
    }

    /**
     * @return \Spryker\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface[]
     */
    public function getTaxChangePlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface
     */
    public function createPriceCalculationHelper()
    {
        return new PriceCalculationHelper();
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\TaxDefault
     */
    public function createTaxDefault()
    {
        return new TaxDefault($this->getStore(), $this->getConfig());
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(TaxDependencyProvider::STORE_CONFIG);
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\AccruedTaxCalculator
     */
    public function createAccruedTaxCalculator()
    {
        return new AccruedTaxCalculator($this->createPriceCalculationHelper());
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\Calculator\TaxAmountAfterCancellationCalculator
     */
    public function createTaxAmountAfterCancellationCalculator()
    {
        return new TaxAmountAfterCancellationCalculator($this->createAccruedTaxCalculator());
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\Calculator\TaxAmountCalculator
     */
    public function createTaxAmountCalculator()
    {
        return new TaxAmountCalculator($this->createAccruedTaxCalculator());
    }
}
