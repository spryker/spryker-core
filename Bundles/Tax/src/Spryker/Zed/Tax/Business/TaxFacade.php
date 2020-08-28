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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
