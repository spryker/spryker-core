<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Persistence\Mapper;

use Generated\Shared\Transfer\MerchantStockTransfer;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStock;

class MerchantStockMapper
{
    /**
     * @param \Orm\Zed\MerchantStock\Persistence\SpyMerchantStock $merchantStockEntity
     * @param \Generated\Shared\Transfer\MerchantStockTransfer $merchantStockTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStockTransfer
     */
    public function mapMerchantStockEntityToMerchantStockTransfer(
        SpyMerchantStock $merchantStockEntity,
        MerchantStockTransfer $merchantStockTransfer
    ): MerchantStockTransfer {
        return $merchantStockTransfer->fromArray($merchantStockEntity->toArray(), true);
    }
}
