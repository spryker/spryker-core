<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantProductStorageTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;

class MerchantProductStorageMapper
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
        return $merchantProductTransfer->setIdMerchant($merchantProductEntity->getFkMerchant())
            ->setIdProductAbstract($merchantProductEntity->getFkProductAbstract())
            ->setIsShared($merchantProductEntity->getIsShared())
            ->setSku($merchantProductEntity->getProductAbstract()->getSku())
            ->setMerchantReference($merchantProductEntity->getMerchant()->getMerchantReference());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     * @param \Generated\Shared\Transfer\MerchantProductStorageTransfer $merchantProductStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductStorageTransfer
     */
    public function mapMerchantProductTransferToMerchantProductStorageTransfer(
        MerchantProductTransfer $merchantProductTransfer,
        MerchantProductStorageTransfer $merchantProductStorageTransfer
    ): MerchantProductStorageTransfer {
        return $merchantProductStorageTransfer->fromArray($merchantProductTransfer->toArray(), true);
    }
}
