<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductPackagingUnitAmountBuilder;
use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductPackagingUnitHelper extends Module
{
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

        $productPackagingUnitAmountTransfer = (new ProductPackagingUnitAmountBuilder())
            ->seed($amountOverride)
            ->build();

        $productPackagingUnitEntity = $this->storeProductPackagingUnit($productPackagingUnitTransfer, $productPackagingUnitAmountTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productPackagingUnitEntity) {
            $this->cleanupProductPackagingUnit($productPackagingUnitEntity);
        });

        return $productPackagingUnitEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntity
     * @param \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer $packagingUnitAmountTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingUnitEntityTransfer
     */
    protected function storeProductPackagingUnit(
        SpyProductPackagingUnitEntityTransfer $productPackagingUnitEntity,
        ProductPackagingUnitAmountTransfer $packagingUnitAmountTransfer
    ): SpyProductPackagingUnitEntityTransfer {
        $spyProductPackagingUnitEntity = $this->getProductPackagingUnitQuery()
            ->filterByIdProductPackagingUnit($productPackagingUnitEntity->getIdProductPackagingUnit())
            ->findOneOrCreate();

        $spyProductPackagingUnitEntity->fromArray($productPackagingUnitEntity->modifiedToArray());
        $spyProductPackagingUnitEntity->fromArray($packagingUnitAmountTransfer->toArray());

        $spyProductPackagingUnitEntity->save();

        $this->debug(sprintf('Inserted product packaging unit with ID: %d', $spyProductPackagingUnitEntity->getIdProductPackagingUnit()));

        $productPackagingUnitEntity->fromArray($spyProductPackagingUnitEntity->toArray(), true);

        return $productPackagingUnitEntity;
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
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    protected function getProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return SpyProductPackagingUnitQuery::create();
    }
}
