<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductPackagingUnitTypeHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer
     */
    public function haveProductPackagingUnitType(array $override = []): SpyProductPackagingUnitTypeEntityTransfer
    {
        $productPackagingUnitTypeTransfer = (new SpyProductPackagingUnitTypeEntityTransfer());
        $productPackagingUnitTypeTransfer->fromArray($override, true);

        $productPackagingUnitTypeEntity = $this->storeProductPackagingUnitType($productPackagingUnitTypeTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productPackagingUnitTypeEntity) {
            $this->cleanupProductPackagingUnitType($productPackagingUnitTypeEntity);
        });

        return $productPackagingUnitTypeEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer $productPackagingUnitTypeEntity
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer
     */
    protected function storeProductPackagingUnitType(SpyProductPackagingUnitTypeEntityTransfer $productPackagingUnitTypeEntity)
    {
        $spyProductPackagingUnitTypeEntity = $this->getProductPackagingUnitTypeQuery()
            ->filterByName($productPackagingUnitTypeEntity->getName())
            ->findOneOrCreate();

        $spyProductPackagingUnitTypeEntity->save();

        $this->debug(sprintf('Inserted product packaging unit type with name: %s', $productPackagingUnitTypeEntity->getName()));

        $productPackagingUnitTypeEntity->fromArray($spyProductPackagingUnitTypeEntity->toArray(), true);

        return $productPackagingUnitTypeEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitTypeEntityTransfer $productPackagingUnitTypeEntity
     *
     * @return void
     */
    protected function cleanupProductPackagingUnitType(SpyProductPackagingUnitTypeEntityTransfer $productPackagingUnitTypeEntity)
    {
        $this->debug(sprintf('Deleting product packaging unit type with name: %s', $productPackagingUnitTypeEntity->getName()));

        $this->getProductPackagingUnitTypeQuery()
            ->findByName($productPackagingUnitTypeEntity->getIdProductPackagingUnitType())
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    protected function getProductPackagingUnitTypeQuery(): SpyProductPackagingUnitTypeQuery
    {
        return SpyProductPackagingUnitTypeQuery::create();
    }
}
