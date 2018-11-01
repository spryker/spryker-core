<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProductQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductPackagingLeadProductHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer
     */
    public function haveProductPackagingLeadProduct(array $override = []): SpyProductPackagingLeadProductEntityTransfer
    {
        $productPackagingLeadProductTransfer = (new SpyProductPackagingLeadProductEntityTransfer());
        $productPackagingLeadProductTransfer->fromArray($override, true);

        $productPackagingLeadProductEntity = $this->storeProductPackagingLeadProduct($productPackagingLeadProductTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productPackagingLeadProductEntity) {
            $this->cleanupProductPackagingLeadProduct($productPackagingLeadProductEntity);
        });

        return $productPackagingLeadProductEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer $productPackagingLeadProductEntity
     *
     * @return \Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer
     */
    protected function storeProductPackagingLeadProduct(SpyProductPackagingLeadProductEntityTransfer $productPackagingLeadProductEntity)
    {
        $spyProductPackagingLeadProductEntity = $this->getProductPackagingLeadProductQuery()
            ->filterByFkProduct($productPackagingLeadProductEntity->getFkProduct())
            ->filterByFkProductAbstract($productPackagingLeadProductEntity->getFkProductAbstract())
            ->findOneOrCreate();

        $spyProductPackagingLeadProductEntity->save();

        $this->debug(sprintf('Inserted product packaging lead product with ID: %d', $spyProductPackagingLeadProductEntity->getIdProductPackagingLeadProduct()));

        $productPackagingLeadProductEntity->fromArray($spyProductPackagingLeadProductEntity->toArray(), true);

        return $productPackagingLeadProductEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductPackagingLeadProductEntityTransfer $productPackagingLeadProductEntity
     *
     * @return void
     */
    protected function cleanupProductPackagingLeadProduct(SpyProductPackagingLeadProductEntityTransfer $productPackagingLeadProductEntity)
    {
        $this->debug(sprintf('Deleting product packaging lead product with ID: %d', $productPackagingLeadProductEntity->getIdProductPackagingLeadProduct()));

        $this->getProductPackagingLeadProductQuery()
            ->findByIdProductPackagingLeadProduct($productPackagingLeadProductEntity->getIdProductPackagingLeadProduct())
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductPackagingLeadProduct\Persistence\SpyProductPackagingLeadProductQuery
     */
    protected function getProductPackagingLeadProductQuery(): SpyProductPackagingLeadProductQuery
    {
        return SpyProductPackagingLeadProductQuery::create();
    }
}
