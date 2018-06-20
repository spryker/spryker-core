<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit;

use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitReader implements ProductPackagingUnitReaderInterface
{
    /**
     * default values for packaging unit storage values.
     */
    protected const PRODUCT_ABSTRACT_STORAGE_DEFAULT_VALUES = [
        ProductPackagingUnitAmountTransfer::DEFAULT_AMOUNT => 1,
        ProductPackagingUnitAmountTransfer::IS_VARIABLE => false,
        ProductPackagingUnitAmountTransfer::AMOUNT_MIN => 1,
        ProductPackagingUnitAmountTransfer::AMOUNT_INTERVAL => 1,
    ];

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $repository
     */
    public function __construct(
        ProductPackagingUnitRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @param int $productPackagingUnitId
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function getProductPackagingUnitById(
        int $productPackagingUnitId
    ): ?ProductPackagingUnitTransfer {
        $productPackagingUnitTransfer = $this->repository->getProductPackagingUnitById($productPackagingUnitId);

        if ($productPackagingUnitTransfer && !$productPackagingUnitTransfer->getProductPackagingUnitAmount()) {
            $productPackagingUnitTransfer->setProductPackagingUnitAmount(
                $this->createDefaultProductPackagingUnitAmountTransfer()
            );
        }

        return $productPackagingUnitTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer
     */
    protected function createDefaultProductPackagingUnitAmountTransfer(): ProductPackagingUnitAmountTransfer
    {
        return (new ProductPackagingUnitAmountTransfer())
            ->fromArray(
                static::PRODUCT_ABSTRACT_STORAGE_DEFAULT_VALUES
            );
    }
}
