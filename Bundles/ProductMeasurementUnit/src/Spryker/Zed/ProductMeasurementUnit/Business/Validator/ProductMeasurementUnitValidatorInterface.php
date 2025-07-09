<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Validator;

use Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer;

interface ProductMeasurementUnitValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $requestTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer $responseTransfer
     * @param array $invalidCodes
     *
     * @return void
     */
    public function validatePrecision(
        ProductMeasurementUnitCollectionRequestTransfer $requestTransfer,
        ProductMeasurementUnitCollectionResponseTransfer $responseTransfer,
        array &$invalidCodes
    ): void;

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer $productMeasurementUnitCollectionResponseTransfer
     * @param array $invalidCodes
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return void
     */
    public function validateProductMeasurementUnitsExist(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer,
        ProductMeasurementUnitCollectionResponseTransfer $productMeasurementUnitCollectionResponseTransfer,
        array &$invalidCodes
    ): void;

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer $productMeasurementUnitCollectionResponseTransfer
     * @param array $invalidCodes
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return void
     */
    public function validateProductMeasurementUnitsNotExist(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer,
        ProductMeasurementUnitCollectionResponseTransfer $productMeasurementUnitCollectionResponseTransfer,
        array &$invalidCodes
    ): void;

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    public function validateDeleteCriteria(
        ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer;
}
