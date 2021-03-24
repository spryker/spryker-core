<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;

class MerchantMapper
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $merchantEntity
     *
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchant
     */
    public function mapMerchantTransferToMerchantEntity(
        MerchantTransfer $merchantTransfer,
        SpyMerchant $merchantEntity
    ): SpyMerchant {
        $merchantEntity->fromArray(
            $merchantTransfer->modifiedToArray(false)
        );

        return $merchantEntity;
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $merchantEntity
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function mapMerchantEntityToMerchantTransfer(
        SpyMerchant $merchantEntity,
        MerchantTransfer $merchantTransfer
    ): MerchantTransfer {
        return $merchantTransfer->fromArray(
            $merchantEntity->toArray(),
            true
        );
    }
}
