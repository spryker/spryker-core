<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitTypeReader implements ProductPackagingUnitTypeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsReader
     */
    protected $translationsReader;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $repository
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitType\ProductPackagingUnitTypeTranslationsReader $translationsReader
     */
    public function __construct(
        ProductPackagingUnitRepositoryInterface $repository,
        ProductPackagingUnitTypeTranslationsReader $translationsReader
    ) {
        $this->repository = $repository;
        $this->translationsReader = $translationsReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getProductPackagingUnitTypeByName(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeTransfer->requireName();

        $productPackagingUnitTypeTransfer = $this->repository->getProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer->getName());
        $productPackagingUnitTypeTransfer = $this->translationsReader->hydrateTranslations($productPackagingUnitTypeTransfer);

        return $productPackagingUnitTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getProductPackagingUnitTypeById(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeTransfer->requireIdProductPackagingUnitType();

        $productPackagingUnitTypeTransfer = $this->repository->getProductPackagingUnitTypeById($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType());
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
}
