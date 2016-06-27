<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Tax\Business\TaxBusinessFactory getFactory()
 */
class TaxFacade extends AbstractFacade implements TaxFacadeInterface
{

    /**
     * @api
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\TaxRateCollectionTransfer
     */
    public function getTaxRates()
    {
        return $this->getFactory()->createReaderModel()->getTaxRates();
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function getTaxRate($id)
    {
        return $this->getFactory()->createReaderModel()->getTaxRate($id);
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function taxRateExists($id)
    {
        return $this->getFactory()->createReaderModel()->taxRateExists($id);
    }

    /**
     * @api
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\TaxSetCollectionTransfer
     */
    public function getTaxSets()
    {
        return $this->getFactory()->createReaderModel()->getTaxSets();
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function getTaxSet($id)
    {
        return $this->getFactory()->createReaderModel()->getTaxSet($id);
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function taxSetExists($id)
    {
        return $this->getFactory()->createReaderModel()->taxSetExists($id);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRate
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function createTaxRate(TaxRateTransfer $taxRate)
    {
        return $this->getFactory()->createWriterModel()->createTaxRate($taxRate);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return int
     */
    public function updateTaxRate(TaxRateTransfer $taxRateTransfer)
    {
        return $this->getFactory()->createWriterModel()->updateTaxRate($taxRateTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSet
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\MissingTaxRateException
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function createTaxSet(TaxSetTransfer $taxSet)
    {
        return $this->getFactory()->createWriterModel()->createTaxSet($taxSet);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\MissingTaxRateException
     *
     * @return int
     */
    public function updateTaxSet(TaxSetTransfer $taxSetTransfer)
    {
        return $this->getFactory()->createWriterModel()->updateTaxSet($taxSetTransfer);
    }

    /**
     * @api
     *
     * @param int $taxSetId
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return int
     */
    public function addTaxRateToTaxSet($taxSetId, TaxRateTransfer $taxRateTransfer)
    {
        return $this->getFactory()->createWriterModel()->addTaxRateToTaxSet($taxSetId, $taxRateTransfer);
    }

    /**
     * @api
     *
     * @param int $taxSetId
     * @param int $taxRateId
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\MissingTaxRateException
     *
     * @return int
     */
    public function removeTaxRateFromTaxSet($taxSetId, $taxRateId)
    {
        return $this->getFactory()->createWriterModel()->removeTaxRateFromTaxSet($taxSetId, $taxRateId);
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function deleteTaxRate($id)
    {
        return $this->getFactory()->createWriterModel()->deleteTaxRate($id);
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function deleteTaxSet($id)
    {
        return $this->getFactory()->createWriterModel()->deleteTaxSet($id);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateTaxTotals(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createTaxCalculator()->recalculate($quoteTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateProductItemTaxRate(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createProductItemTaxRateCalculator()->recalculate($quoteTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateTaxItemAmount(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createTaxItemAmountCalculator()->recalculate($quoteTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateExpenseTaxAmount(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createExpenseTaxCalculator()->recalculate($quoteTransfer);
    }


    /**
     * @api
     *
     * @param int $grossPrice
     * @param float $taxRate
     *
     * @return int
     */
    public function getTaxAmountFromGrossPrice($grossPrice, $taxRate)
    {
        return $this->getFactory()->createPriceCalculationHelper()->getTaxValueFromPrice($grossPrice, $taxRate);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultTaxCountry()
    {
        return $this->getFactory()->createTaxDefault()->getDefaultCountry();
    }

    /**
     * @api
     *
     * @return float
     */
    public function getDefaultTaxRate()
    {
        return $this->getFactory()->createTaxDefault()->getDefaultTaxRate();
    }

    /**
     * @api
     *
     * @param int $grossPrice
     * @param float $taxRate
     *
     * @return int
     */
    public function getAccruedTaxAmountFromGrossPrice($grossPrice, $taxRate)
    {
        return $this->getFactory()->createAccruedTaxCalculator()->getTaxValueFromPrice($grossPrice, $taxRate);
    }

}
