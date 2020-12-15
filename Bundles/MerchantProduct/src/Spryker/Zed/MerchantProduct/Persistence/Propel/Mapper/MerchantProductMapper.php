<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantProductTransfer;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;

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
        return $merchantProductTransfer->fromArray($merchantProductEntity->toArray(), true)
            ->setIdProductAbstract($merchantProductEntity->getFkProductAbstract())
            ->setIdMerchant($merchantProductEntity->getFkMerchant());
    }
}
