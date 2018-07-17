<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\SpyProductPackagingUnitAmountEntityTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitAmountQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductPackagingUnitHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param array $override
     * @param array|null $amountOverride
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer
     */
    public function haveProductPackagingUnit(array $override = [], ?array $amountOverride = []): SpyProductPackagingUnitEntityTransfer
    {
        $productPackagingUnitTransfer = (new SpyProductPackagingUnitEntityTransfer());
        $productPackagingUnitTransfer->fromArray($override, true);

        $productPackagingUnitEntity = $this->storeProductPackagingUnit($productPackagingUnitTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productPackagingUnitEntity) {
            $this->cleanupProductPackagingUnit($productPackagingUnitEntity);
        });

        if (!empty($amountOverride)) {
            $productPackagingUnitAmountEntity = (new SpyProductPackagingUnitAmountEntityTransfer());
            $amountOverride[SpyProductPackagingUnitAmountEntityTransfer::FK_PRODUCT_PACKAGING_UNIT] = $productPackagingUnitEntity->getIdProductPackagingUnit();
            $productPackagingUnitAmountEntity->fromArray($amountOverride, true);

            $this->storeProductPackagingUnitAmount($productPackagingUnitAmountEntity);

            $this->getDataCleanupHelper()->_addCleanup(function () use ($productPackagingUnitAmountEntity) {
                $this->cleanupProductPackagingUnitAmount($productPackagingUnitAmountEntity);
            });
        }

        return $productPackagingUnitEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntity
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer
     */
    protected function storeProductPackagingUnit(SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntity)
    {
        $spyProductPackagingUnitEntity = $this->getProductPackagingUnitQuery()
            ->filterByIdProductPackagingUnit($productPackagingUnitEntity->getIdProductPackagingUnit())
            ->findOneOrCreate();

        $spyProductPackagingUnitEntity->fromArray($productPackagingUnitEntity->modifiedToArray());

        $spyProductPackagingUnitEntity->save();

        $this->debug(sprintf('Inserted product packaging unit with ID: %d', $spyProductPackagingUnitEntity->getIdProductPackagingUnit()));

        $productPackagingUnitEntity->fromArray($spyProductPackagingUnitEntity->toArray(), true);

        return $productPackagingUnitEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitAmountEntityTransfer $productPackagingUnitAmountEntity
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitAmountEntityTransfer
     */
    protected function storeProductPackagingUnitAmount(SpyProductPackagingUnitAmountEntityTransfer $productPackagingUnitAmountEntity)
    {
        $spyProductPackagingUnitAmountEntity = $this->getProductPackagingUnitAmountQuery()
            ->filterByFkProductPackagingUnit($productPackagingUnitAmountEntity->getFkProductPackagingUnit())
            ->findOneOrCreate();

        $spyProductPackagingUnitAmountEntity->fromArray($productPackagingUnitAmountEntity->modifiedToArray());

        $spyProductPackagingUnitAmountEntity->save();

        $this->debug(sprintf('Inserted product packaging unit amount with ID: %d', $spyProductPackagingUnitAmountEntity->getIdProductPackagingUnitAmount()));

        $productPackagingUnitAmountEntity->fromArray($spyProductPackagingUnitAmountEntity->toArray(), true);

        return $productPackagingUnitAmountEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntity
     *
     * @return void
     */
    protected function cleanupProductPackagingUnit(SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntity)
    {
        $this->debug(sprintf('Deleting product packaging unit with ID: %d', $productPackagingUnitEntity->getIdProductPackagingUnit()));

        $this->getProductPackagingUnitQuery()
            ->findByIdProductPackagingUnit($productPackagingUnitEntity->getIdProductPackagingUnit())
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer $productPackagingUnitAmountEntity
     *
     * @return void
     */
    protected function cleanupProductPackagingUnitAmount(SpyProductPackagingUnitAmountEntityTransfer $productPackagingUnitAmountEntity)
    {
        $this->debug(sprintf('Deleting product packaging unit amount with ID: %d', $productPackagingUnitAmountEntity->getIdProductPackagingUnitAmount()));

        $this->getProductPackagingUnitAmountQuery()
            ->findByIdProductPackagingUnitAmount($productPackagingUnitAmountEntity->getIdProductPackagingUnitAmount())
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return SpyProductPackagingUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitAmountQuery
     */
    protected function getProductPackagingUnitAmountQuery(): SpyProductPackagingUnitAmountQuery
    {
        return SpyProductPackagingUnitAmountQuery::create();
    }
}
