<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Deleter;

use Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductMeasurementUnit\Business\Validator\ProductMeasurementUnitValidatorInterface;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitEntityManagerInterface;

class ProductMeasurementUnitDeleter implements ProductMeasurementUnitDeleterInterface
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
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    public function delete(
        ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($productMeasurementUnitCollectionDeleteCriteriaTransfer) {
            return $this->executeDeleteTransaction($productMeasurementUnitCollectionDeleteCriteriaTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    protected function executeDeleteTransaction(
        ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer {
        $productMeasurementUnitCollectionResponseTransfer = $this->productMeasurementUnitValidator->validateDeleteCriteria($productMeasurementUnitCollectionDeleteCriteriaTransfer);

        if (count($productMeasurementUnitCollectionResponseTransfer->getErrors()) && $productMeasurementUnitCollectionDeleteCriteriaTransfer->getIsTransactional() !== false) {
            return $productMeasurementUnitCollectionResponseTransfer;
        }

        foreach ($productMeasurementUnitCollectionResponseTransfer->getProductMeasurementUnits() as $productMeasurementUnitTransfer) {
            $this->productMeasurementUnitEntityManager->deleteProductMeasurementUnit($productMeasurementUnitTransfer->getIdProductMeasurementUnit());
        }

        return $productMeasurementUnitCollectionResponseTransfer;
    }
}
