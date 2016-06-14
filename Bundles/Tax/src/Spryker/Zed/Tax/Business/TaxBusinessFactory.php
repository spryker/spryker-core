<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Tax\Business\Model\AccruedTaxCalculator;
use Spryker\Zed\Tax\Business\Model\ExpenseTaxCalculator;
use Spryker\Zed\Tax\Business\Model\ItemTaxCalculator;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelper;
use Spryker\Zed\Tax\Business\Model\TaxCalculation;
use Spryker\Zed\Tax\Business\Model\TaxReader;
use Spryker\Zed\Tax\Business\Model\TaxWriter;

/**
 * @method \Spryker\Zed\Tax\TaxConfig getConfig()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainer getQueryContainer()
 */
class TaxBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Tax\Business\Model\TaxReaderInterface
     */
    public function createReaderModel()
    {
        return new TaxReader(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\TaxWriterInterface
     */
    public function createWriterModel()
    {
        return new TaxWriter(
            $this->getQueryContainer(),
            $this->getTaxChangePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Tax\Dependency\Plugin\TaxChangePluginInterface[]
     */
    public function getTaxChangePlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\TaxCalculation
     */
    public function createTaxCalculator()
    {
        return new TaxCalculation();
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\TaxCalculation
     */
    public function createTaxItemAmountCalculator()
    {
        return new ItemTaxCalculator($this->createAccruedTaxCalculator());
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\Model\Calculator\ExpenseTotalsCalculator
     */
    public function createExpenseTaxCalculator()
    {
       return new ExpenseTaxCalculator($this->createAccruedTaxCalculator());
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface
     */
    public function createPriceCalculationHelper()
    {
        return new PriceCalculationHelper();
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\AccruedTaxCalculator
     */
    public function createAccruedTaxCalculator()
    {
        return new AccruedTaxCalculator($this->createPriceCalculationHelper());
    }

}
