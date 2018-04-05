<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductQuantity\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\SpyProductQuantityEntityBuilder;
use Generated\Shared\Transfer\SpyProductQuantityEntityTransfer;
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
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer
     */
    public function haveProductQuantity($idProduct, array $productQuantityOverride = [])
    {
        $productQuantityEntity = (new SpyProductQuantityEntityBuilder())
            ->build()
            ->fromArray($productQuantityOverride, true)
            ->setFkProduct($idProduct);

        $productQuantityEntity = $this->storeProductQuantity($productQuantityEntity);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productQuantityEntity) {
            $this->cleanupProductQuantity($productQuantityEntity->getIdProductQuantity());
        });

        return $productQuantityEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer $productQuantityEntity
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer
     */
    protected function storeProductQuantity(SpyProductQuantityEntityTransfer $productQuantityEntity)
    {
        $spyProductQuantityEntity = $this->getProductQuantityQuery()
            ->filterByFkProduct($productQuantityEntity->getFkProduct())
            ->findOneOrCreate();

        $spyProductQuantityEntity->fromArray($productQuantityEntity->modifiedToArray());
        $spyProductQuantityEntity->save();

        $this->debug(sprintf('Inserted product quantity for product concrete: %d', $productQuantityEntity->getFkProduct()));

        $productQuantityEntity->fromArray($spyProductQuantityEntity->toArray(), true);

        return $productQuantityEntity;
    }

    /**
     * @param int $idProductQuantity
     *
     * @return void
     */
    protected function cleanupProductQuantity($idProductQuantity)
    {
        $this->debug(sprintf('Deleting product quantity: %d', $idProductQuantity));

        $this->getProductQuantityQuery()
            ->findByIdProductQuantity($idProductQuantity)
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductQuantity\Persistence\SpyProductQuantityQuery
     */
    protected function getProductQuantityQuery()
    {
        return SpyProductQuantityQuery::create();
    }
}
