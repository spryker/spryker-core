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
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsReaderInterface
     */
    protected $translationsReader;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $repository
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsReaderInterface $translationsReader
     */
    public function __construct(
        ProductPackagingUnitRepositoryInterface $repository,
        ProductPackagingUnitTypeTranslationsReaderInterface $translationsReader
    ) {
        $this->repository = $repository;
        $this->translationsReader = $translationsReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @throws \Spryker\Zed\ProductPackagingUnit\Business\Exception\ProductPackagingUnitTypeNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getProductPackagingUnitTypeByName(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeTransfer->requireName();
        $productPackagingUnitTypeName = $productPackagingUnitTypeTransfer->getName();
        $productPackagingUnitTypeTransfer = $this->repository->getProductPackagingUnitTypeByName($productPackagingUnitTypeName);

        if ($productPackagingUnitTypeTransfer === null) {
            throw new ProductPackagingUnitTypeNotFoundException(
                sprintf(static::ERROR_NO_PRODUCT_PACKAGING_UNIT_TYPE_BY_NAME, $productPackagingUnitTypeName)
            );
        }

        $productPackagingUnitTypeTransfer = $this->translationsReader->hydrateTranslations($productPackagingUnitTypeTransfer);

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
        $productPackagingUnitTypeId = $productPackagingUnitTypeTransfer->getIdProductPackagingUnitType();
        $productPackagingUnitTypeTransfer = $this->repository->getProductPackagingUnitTypeById($productPackagingUnitTypeId);

        if ($productPackagingUnitTypeTransfer === null) {
            throw new ProductPackagingUnitTypeNotFoundException(
                sprintf(static::ERROR_NO_PRODUCT_PACKAGING_UNIT_TYPE_BY_ID, $productPackagingUnitTypeId)
            );
        }

        $productPackagingUnitTypeTransfer = $this->translationsReader->hydrateTranslations($productPackagingUnitTypeTransfer);

        return $productPackagingUnitTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return int
     */
    public function getCountProductPackagingUnitsForType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): int {
        $productPackagingUnitTypeTransfer->requireIdProductPackagingUnitType();

        return $this->repository->getCountProductPackagingUnitsForTypeById($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType());
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
