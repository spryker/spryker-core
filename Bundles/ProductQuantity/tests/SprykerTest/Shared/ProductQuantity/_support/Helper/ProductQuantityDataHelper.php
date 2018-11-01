<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductQuantity\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductQuantityBuilder;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Orm\Zed\ProductQuantity\Persistence\SpyProductQuantityQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductQuantityDataHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param int $idProduct
     * @param array $productQuantityOverride
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer
     */
    public function haveProductQuantity($idProduct, array $productQuantityOverride = []): ProductQuantityTransfer
    {
        $productQuantityTransfer = (new ProductQuantityBuilder())
            ->build()
            ->fromArray($productQuantityOverride, true)
            ->setFkProduct($idProduct);

        $productQuantityTransfer = $this->storeProductQuantity($productQuantityTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productQuantityTransfer) {
            $this->cleanupProductQuantity($productQuantityTransfer->getIdProductQuantity());
        });

        return $productQuantityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer
     */
    protected function storeProductQuantity(ProductQuantityTransfer $productQuantityTransfer): ProductQuantityTransfer
    {
        $spyProductQuantityEntity = $this->getProductQuantityQuery()
            ->filterByFkProduct($productQuantityTransfer->getFkProduct())
            ->findOneOrCreate();

        $spyProductQuantityEntity->fromArray($productQuantityTransfer->modifiedToArray());
        $spyProductQuantityEntity->save();

        $this->debug(sprintf('Inserted product quantity for product concrete: %d', $productQuantityTransfer->getFkProduct()));

        $productQuantityTransfer->fromArray($spyProductQuantityEntity->toArray(), true);

        return $productQuantityTransfer;
    }

    /**
     * @param int $idProductQuantity
     *
     * @return void
     */
    protected function cleanupProductQuantity($idProductQuantity): void
    {
        $this->debug(sprintf('Deleting product quantity: %d', $idProductQuantity));

        $this->getProductQuantityQuery()
            ->findByIdProductQuantity($idProductQuantity)
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductQuantity\Persistence\SpyProductQuantityQuery
     */
    protected function getProductQuantityQuery(): SpyProductQuantityQuery
    {
        return SpyProductQuantityQuery::create();
    }
}
