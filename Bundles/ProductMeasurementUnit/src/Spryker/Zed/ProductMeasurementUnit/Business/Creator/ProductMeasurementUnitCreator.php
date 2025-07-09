<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Creator;

use Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductMeasurementUnit\Business\Validator\ProductMeasurementUnitValidatorInterface;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitEntityManagerInterface;

class ProductMeasurementUnitCreator implements ProductMeasurementUnitCreatorInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Business\Validator\ProductMeasurementUnitValidatorInterface $productMeasurementUnitValidator
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitEntityManagerInterface $productMeasurementUnitEntityManager
     */
    public function __construct(
        protected ProductMeasurementUnitValidatorInterface $productMeasurementUnitValidator,
        protected ProductMeasurementUnitEntityManagerInterface $productMeasurementUnitEntityManager
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    public function create(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productMeasurementUnitCollectionRequestTransfer) {
            return $this->executeCreateTransaction($productMeasurementUnitCollectionRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    protected function executeCreateTransaction(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer {
        $productMeasurementUnitCollectionResponseTransfer = new ProductMeasurementUnitCollectionResponseTransfer();

        $invalidCodes = [];
        $this->productMeasurementUnitValidator->validateProductMeasurementUnitsNotExist($productMeasurementUnitCollectionRequestTransfer, $productMeasurementUnitCollectionResponseTransfer, $invalidCodes);
        $this->productMeasurementUnitValidator->validatePrecision($productMeasurementUnitCollectionRequestTransfer, $productMeasurementUnitCollectionResponseTransfer, $invalidCodes);

        if (count($productMeasurementUnitCollectionResponseTransfer->getErrors()) > 0 && $productMeasurementUnitCollectionRequestTransfer->getIsTransactional() !== false) {
            return $productMeasurementUnitCollectionResponseTransfer;
        }

        foreach ($productMeasurementUnitCollectionRequestTransfer->getProductMeasurementUnits() as $unitTransfer) {
            if (in_array($unitTransfer->getCode(), $invalidCodes, true)) {
                continue;
            }

            $savedUnitTransfer = $this->productMeasurementUnitEntityManager->saveProductMeasurementUnit($unitTransfer);
            $productMeasurementUnitCollectionResponseTransfer->addProductMeasurementUnit($savedUnitTransfer);
        }

        return $productMeasurementUnitCollectionResponseTransfer;
    }
}
