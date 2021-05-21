<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;
use Propel\Runtime\Collection\ObjectCollection;

class MerchantProductMapper
{
    /**
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract $merchantProductAbstractEntity
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer
     */
    public function mapMerchantProductEntityToMerchantProductTransfer(
        SpyMerchantProductAbstract $merchantProductAbstractEntity,
        MerchantProductTransfer $merchantProductTransfer
    ): MerchantProductTransfer {
        $merchantProductTransfer->fromArray($merchantProductAbstractEntity->toArray(), true)
            ->setIdProductAbstract($merchantProductAbstractEntity->getFkProductAbstract())
            ->setIdMerchant($merchantProductAbstractEntity->getFkMerchant());

        $this->mapConcreteProductsToMerchantProductTransfer(
            $merchantProductTransfer,
            $merchantProductAbstractEntity->getProductAbstract()->getSpyProducts()
        );

        return $merchantProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract $merchantProductAbstractEntity
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract
     */
    public function mapMerchantProductTransferToMerchantProductAbstractEntity(
        MerchantProductTransfer $merchantProductTransfer,
        SpyMerchantProductAbstract $merchantProductAbstractEntity
    ): SpyMerchantProductAbstract {
        $merchantProductAbstractEntity->fromArray($merchantProductTransfer->toArray());
        $merchantProductAbstractEntity->setFkMerchant($merchantProductTransfer->getIdMerchantOrFail());
        $merchantProductAbstractEntity->setFkProductAbstract($merchantProductTransfer->getIdProductAbstractOrFail());

        return $merchantProductAbstractEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProduct[] $productEntities
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer
     */
    protected function mapConcreteProductsToMerchantProductTransfer(
        MerchantProductTransfer $merchantProductTransfer,
        ObjectCollection $productEntities
    ): MerchantProductTransfer {
        foreach ($productEntities as $productEntity) {
            $productConcreteTransfer = (new ProductConcreteTransfer())->fromArray($productEntity->toArray(), true);
            $productConcreteTransfer->setIdProductConcrete($productEntity->getIdProduct());

            $merchantProductTransfer->addProduct($productConcreteTransfer);
        }

        return $merchantProductTransfer;
    }
}
