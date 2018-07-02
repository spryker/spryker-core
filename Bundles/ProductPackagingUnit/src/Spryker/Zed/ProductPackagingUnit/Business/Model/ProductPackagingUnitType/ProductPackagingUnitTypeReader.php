<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeNotFoundException;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitTypeReader implements ProductPackagingUnitTypeReaderInterface
{
    protected const ERROR_NO_PRODUCT_PACKAGING_UNIT_TYPE_BY_NAME = 'Product packaging unit type was not found for name "%s".';
    protected const ERROR_NO_PRODUCT_PACKAGING_UNIT_TYPE_BY_ID = 'Product packaging unit type was not found for ID "%d".';

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationReaderInterface
     */
    protected $translationReader;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $repository
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationReaderInterface $translationReader
     */
    public function __construct(
        ProductPackagingUnitRepositoryInterface $repository,
        ProductPackagingUnitTypeTranslationReaderInterface $translationReader
    ) {
        $this->repository = $repository;
        $this->translationReader = $translationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer|null
     */
    public function findProductPackagingUnitTypeByName(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeTransfer->requireName();
        $productPackagingUnitTypeName = $productPackagingUnitTypeTransfer->getName();
        $productPackagingUnitTypeTransfer = $this->repository->findProductPackagingUnitTypeByName($productPackagingUnitTypeName);

        if ($productPackagingUnitTypeTransfer) {
            $productPackagingUnitTypeTransfer = $this->translationReader->hydrateTranslations($productPackagingUnitTypeTransfer);
        }

        return $productPackagingUnitTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @throws \Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getProductPackagingUnitTypeById(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeTransfer->requireIdProductPackagingUnitType();
        $idProductPackagingUnitType = $productPackagingUnitTypeTransfer->getIdProductPackagingUnitType();
        $productPackagingUnitTypeTransfer = $this->repository->findProductPackagingUnitTypeById($idProductPackagingUnitType);

        if ($productPackagingUnitTypeTransfer === null) {
            throw new ProductPackagingUnitTypeNotFoundException(
                sprintf(static::ERROR_NO_PRODUCT_PACKAGING_UNIT_TYPE_BY_ID, $idProductPackagingUnitType)
            );
        }

        $productPackagingUnitTypeTransfer = $this->translationReader->hydrateTranslations($productPackagingUnitTypeTransfer);

        return $productPackagingUnitTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return int
     */
    public function countProductPackagingUnitsByTypeId(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): int {
        $productPackagingUnitTypeTransfer->requireIdProductPackagingUnitType();

        return $this->repository->countProductPackagingUnitsByTypeId($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType());
    }

    /**
     * @param array $productPackagingUnitTypeIds
     *
     * @return array
     */
    public function getIdProductAbstractsByIdProductPackagingUnitTypes(array $productPackagingUnitTypeIds): array
    {
        return $this->repository->getIdProductAbstractsByIdProductPackagingUnitTypes($productPackagingUnitTypeIds);
    }
}
