<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Tax\Business\TaxBusinessFactory getFactory()
 * @method \Spryker\Zed\Tax\Persistence\TaxRepositoryInterface getRepository()
 */
class TaxFacade extends AbstractFacade implements TaxFacadeInterface
{
    /**
     * Specification:
     *  - Returns all persisted tax rates
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\TaxRateCollectionTransfer
     */
    public function getTaxRates()
    {
        return $this->getFactory()
            ->createReaderModel()
            ->getTaxRates();
    }

    /**
     *  Specification:
     *  - Returns persisted rate by primary id
     *
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function getTaxRate($id)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->getTaxRate($id);
    }

    /**
     * Specification:
     *  - Check if rate with given primary id exists
     *
     * @api
     *
     * @param int $id
     *
     * @return bool
     */
    public function taxRateExists($id)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->taxRateExists($id);
    }

    /**
     * Specification:
     *  - Get all tax sets
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\TaxSetCollectionTransfer
     */
    public function getTaxSets()
    {
        return $this->getFactory()
            ->createReaderModel()
            ->getTaxSets();
    }

    /**
     * Specification:
     *  - Return tax set by primary id
     *
     * @api
     *
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function getTaxSet($id)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->getTaxSet($id);
    }

    /**
     * Specification:
     *  - Check if tax set exist with given primary id
     *
     * @api
     *
     * @param int $id
     *
     * @return bool
     */
    public function taxSetExists($id)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->taxSetExists($id);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $name
     *
     * @return bool
     */
    public function taxSetWithSameNameExists(string $name): bool
    {
        return $this->getFactory()
            ->createReaderModel()
            ->taxSetWithSameNameExists($name);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $name
     * @param int $idTaxSet
     *
     * @return bool
     */
    public function taxSetWithSameNameAndIdExists(string $name, int $idTaxSet): bool
    {
        return $this->getFactory()
            ->createReaderModel()
            ->taxSetWithSameNameAndIdExists($name, $idTaxSet);
    }

    /**
     * Specification:
     *  - Create new tax rate
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function createTaxRate(TaxRateTransfer $taxRateTransfer)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->createTaxRate($taxRateTransfer);
    }

    /**
     * Specification:
     *  - Update existing tax rate
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return int
     */
    public function updateTaxRate(TaxRateTransfer $taxRateTransfer)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->updateTaxRate($taxRateTransfer);
    }

    /**
     * Specification:
     *  - Create new tax set
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function createTaxSet(TaxSetTransfer $taxSetTransfer)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->createTaxSet($taxSetTransfer);
    }

    /**
     * Specification:
     *  - Update existing tax set
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @return int
     */
    public function updateTaxSet(TaxSetTransfer $taxSetTransfer)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->updateTaxSet($taxSetTransfer);
    }

    /**
     * Specification:
     *  - Add existing tax rate tax set
     *
     * @api
     *
     * @param int $idTaxSet
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return int
     */
    public function addTaxRateToTaxSet($idTaxSet, TaxRateTransfer $taxRateTransfer)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->addTaxRateToTaxSet($idTaxSet, $taxRateTransfer);
    }

    /**
     * Specification:
     *  - Remove tax reate from existing set
     *
     * @api
     *
     * @param int $idTaxSet
     * @param int $idTaxRate
     *
     * @return int
     */
    public function removeTaxRateFromTaxSet($idTaxSet, $idTaxRate)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->removeTaxRateFromTaxSet($idTaxSet, $idTaxRate);
    }

    /**
     * Specification:
     *  - Remove tax rate
     *
     * @api
     *
     * @param int $id
     *
     * @return void
     */
    public function deleteTaxRate($id)
    {
        $this->getFactory()
            ->createWriterModel()
            ->deleteTaxRate($id);
    }

    /**
     * Specification:
     *  - Removes tax set with all tax rates assigned
     *
     * @api
     *
     * @param int $id
     *
     * @return void
     */
    public function deleteTaxSet($id)
    {
        $this->getFactory()
            ->createWriterModel()
            ->deleteTaxSet($id);
    }

    /**
     * Specification:
     *  - Calculate tax amount from given price and rate
     *  - Value is not rounded
     *
     * @api
     *
     * @param int $grossPrice
     * @param float $taxRate
     *
     * @return float
     */
    public function getTaxAmountFromGrossPrice($grossPrice, $taxRate)
    {
        return $this->getFactory()
            ->createPriceCalculationHelper()
            ->getTaxValueFromPrice($grossPrice, $taxRate, false);
    }

    /**
     * Specification:
     *  - Return default country used when setting rate
     *  - Value is read from config
     *
     * @api
     *
     * @return string
     */
    public function getDefaultTaxCountryIso2Code()
    {
        return $this->getFactory()
            ->createTaxDefault()
            ->getDefaultCountryIso2Code();
    }

    /**
     * Specification:
     *  - Return default tax rate used when setting rate
     *  - Value is read from config
     *
     * @api
     *
     * @return float
     */
    public function getDefaultTaxRate()
    {
        return $this->getFactory()
            ->createTaxDefault()
            ->getDefaultTaxRate();
    }

    /**
     * Specification:
     *  - Calculate tax amount from given price and rate
     *  - Share rounding error between calls to this method.
     *
     * @api
     *
     * @param int $grossPrice
     * @param float $taxRate
     * @param bool $round
     *
     * @return float
     */
    public function getAccruedTaxAmountFromGrossPrice($grossPrice, $taxRate, $round = false)
    {
        return $this->getFactory()
            ->createAccruedTaxCalculator()
            ->getTaxValueFromPrice($grossPrice, $taxRate, $round);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $netPrice
     * @param float $taxRate
     *
     * @return int
     */
    public function getAccruedTaxAmountFromNetPrice($netPrice, $taxRate)
    {
        return $this->getFactory()
            ->createAccruedTaxCalculator()
            ->getTaxValueFromNetPrice($netPrice, $taxRate);
    }

    /**
     * Specification:
     *  - Reset rounding error counter to 0
     *
     * @api
     *
     * @return void
     */
    public function resetAccruedTaxCalculatorRoundingErrorDelta()
    {
         $this->getFactory()
            ->createAccruedTaxCalculator()
            ->resetRoundingErrorDelta();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateTaxAmount(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createTaxAmountCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateTaxAfterCancellation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createTaxAmountAfterCancellationCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateTaxRateAverageAggregation(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createTaxRateAverageAggregationCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idTaxRate
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer|null
     */
    public function findTaxRate(int $idTaxRate): ?TaxRateTransfer
    {
        return $this->getFactory()
            ->createReaderModel()
            ->findTaxRate($idTaxRate);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idTaxSet
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    public function findTaxSet(int $idTaxSet): ?TaxSetTransfer
    {
        return $this->getFactory()
            ->createReaderModel()
            ->findTaxSet($idTaxSet);
    }
}
