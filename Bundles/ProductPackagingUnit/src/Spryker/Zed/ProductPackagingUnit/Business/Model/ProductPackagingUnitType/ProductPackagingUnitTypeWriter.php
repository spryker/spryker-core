<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitEntityManagerInterface;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitTypeWriter implements ProductPackagingUnitTypeWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsWriterInterface
     */
    protected $translationWriter;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitEntityManagerInterface $entityManager
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $repository
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsWriterInterface $translationWriter
     */
    public function __construct(
        ProductPackagingUnitEntityManagerInterface $entityManager,
        ProductPackagingUnitRepositoryInterface $repository,
        ProductPackagingUnitTypeTranslationsWriterInterface $translationWriter
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->translationWriter = $translationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function createProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeTransfer->requireName();

        $this->translationWriter->saveTranslations($productPackagingUnitTypeTransfer);

        return $this->entityManager->saveProductPackagingUnitType($productPackagingUnitTypeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function updateProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeTransfer->requireIdProductPackagingUnitType();

        $this->translationWriter->saveTranslations($productPackagingUnitTypeTransfer);

        return $this->entityManager->saveProductPackagingUnitType($productPackagingUnitTypeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return bool
     */
    public function deleteProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): bool {
        $productPackagingUnitTypeTransfer->requireIdProductPackagingUnitType();

        if ($this->repository
                ->getCountProductPackagingUnitsForTypeById(
                    $productPackagingUnitTypeTransfer->getIdProductPackagingUnitType()
                ) <= 0) {
            $this->translationWriter->deleteTranslations($productPackagingUnitTypeTransfer);

            return $this->entityManager->deleteProductPackagingUnitType($productPackagingUnitTypeTransfer);
        }

        return false;
    }
}
