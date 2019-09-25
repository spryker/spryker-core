<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Tax\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;

/**
 * @method \Spryker\Zed\Tax\Business\TaxBusinessFactory getFactory()
 */
interface TaxFacadeInterface
{
    /**
     * Specification:
     *  - Returns all persisted tax rates
     *
     * @api
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\TaxRateCollectionTransfer
     */
    public function getTaxRates();

    /**
     *  Specification:
     *  - Returns persisted rate by primary id
     *
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function getTaxRate($id);

    /**
     * Specification:
     *  - Check if rate with given primary id exists
     *
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function taxRateExists($id);

    /**
     * Specification:
     *  - Get all tax sets
     *
     * @api
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\TaxSetCollectionTransfer
     */
    public function getTaxSets();

    /**
     * Specification:
     *  - Return tax set by primary id
     *
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function getTaxSet($id);

    /**
     * Specification:
     *  - Check if tax set exist with given primary id
     *
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function taxSetExists($id);

    /**
     * Specification:
     *  - Create new tax rate
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function createTaxRate(TaxRateTransfer $taxRateTransfer);

    /**
     * Specification:
     *  - Update existing tax rate
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return int
     */
    public function updateTaxRate(TaxRateTransfer $taxRateTransfer);

    /**
     * Specification:
     *  - Create new tax set
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\MissingTaxRateException
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function createTaxSet(TaxSetTransfer $taxSetTransfer);

    /**
     * Specification:
     *  - Update existing tax set
     *
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
    public function updateTaxSet(TaxSetTransfer $taxSetTransfer);

    /**
     * Specification:
     *  - Add existing tax rate tax set
     *
     * @api
     *
     * @param int $idTaxSet
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return int
     */
    public function addTaxRateToTaxSet($idTaxSet, TaxRateTransfer $taxRateTransfer);

    /**
     * Specification:
     *  - Remove tax reate from existing set
     *
     * @api
     *
     * @param int $idTaxSet
     * @param int $idTaxRate
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\MissingTaxRateException
     *
     * @return int
     */
    public function removeTaxRateFromTaxSet($idTaxSet, $idTaxRate);

    /**
     * Specification:
     *  - Remove tax rate
     *
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function deleteTaxRate($id);

    /**
     * Specification:
     *  - Removes tax set with all tax rates assigned
     *
     * @api
     *
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function deleteTaxSet($id);

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
    public function getTaxAmountFromGrossPrice($grossPrice, $taxRate);

    /**
     * Specification:
     *  - Return default country used when setting rate
     *  - Value is read from config
     *
     * @api
     *
     * @return string
     */
    public function getDefaultTaxCountryIso2Code();

    /**
     * Specification:
     *  - Return default tax rate used when setting rate
     *  - Value is read from config
     *
     * @api
     *
     * @return float
     */
    public function getDefaultTaxRate();

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
    public function getAccruedTaxAmountFromGrossPrice($grossPrice, $taxRate, $round = false);

    /**
     * Specification:
     *  - Calculate tax amount from given price and rate
     *  - Share rounding error between calls to this method.
     *
     * @api
     *
     * @param int $netPrice
     * @param float $taxRate
     *
     * @return int
     */
    public function getAccruedTaxAmountFromNetPrice($netPrice, $taxRate);

    /**
     * Specification:
     *  - Reset rounding error counter to 0
     *
     * @api
     *
     * @return void
     */
    public function resetAccruedTaxCalculatorRoundingErrorDelta();

    /**
     * Specification:
     * - Calculate tax amount after cancellation
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateTaxAmount(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate tax amount after cancellation
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateTaxAfterCancellation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Calculate tax average for item and expenses, used when recalculate taxable amount after refund
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateTaxRateAverageAggregation(CalculableObjectTransfer $calculableObjectTransfer);

    /**
     * Specification:
     *  - Check if tax set with a given name exist
     *
     * @api
     *
     * @param string $name
     *
     * @return bool
     */
    public function taxSetWithSameNameExists(string $name): bool;

    /**
     * Specification:
     *  - Check if tax set with a given Name and Id exist
     *
     * @api
     *
     * @param string $name
     * @param int $idTaxSet
     *
     * @return bool
     */
    public function taxSetWithSameNameAndIdExists(string $name, int $idTaxSet): bool;

    /**
     * Specification:
     * - Finds tax rate by id.
     * - Returns NULL if tax set does not exist.
     *
     * @api
     *
     * @param int $idTaxRate
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer|null
     */
    public function findTaxRate(int $idTaxRate): ?TaxRateTransfer;

    /**
     * Specification:
     * - Finds tax set by id.
     * - Returns NULL if tax set does not exist.
     *
     * @api
     *
     * @param int $idTaxSet
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    public function findTaxSet(int $idTaxSet): ?TaxSetTransfer;
}
