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
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract $merchantProductEntity
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer
     */
    public function mapMerchantProductEntityToMerchantProductTransfer(
        SpyMerchantProductAbstract $merchantProductEntity,
        MerchantProductTransfer $merchantProductTransfer
    ): MerchantProductTransfer {
        $merchantProductTransfer->fromArray($merchantProductEntity->toArray(), true)
            ->setIdProductAbstract($merchantProductEntity->getFkProductAbstract())
            ->setIdMerchant($merchantProductEntity->getFkMerchant());

        $this->mapConcreteProductsToMerchantProductTransfer(
            $merchantProductTransfer,
            $merchantProductEntity->getProductAbstract()->getSpyProducts()
        );

        return $merchantProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract $merchantProductEntity
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract
     */
    public function mapMerchantProductTransferToMerchantProductEntity(
        MerchantProductTransfer $merchantProductTransfer,
        SpyMerchantProductAbstract $merchantProductEntity
    ): SpyMerchantProductAbstract {
        $merchantProductEntity->fromArray($merchantProductTransfer->toArray());
        $merchantProductEntity->setFkMerchant($merchantProductTransfer->getIdMerchant());
        $merchantProductEntity->setFkProductAbstract($merchantProductTransfer->getIdProductAbstract());

        return $merchantProductEntity;
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
