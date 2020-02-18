<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantOmsProcessTransfer;
use Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcess;

class MerchantOmsMapper
{
    /**
     * @param \Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcess $merchantOmsProcessEntity
     * @param \Generated\Shared\Transfer\MerchantOmsProcessTransfer $merchantOmsProcessTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOmsProcessTransfer
     */
    public function mapMerchantOmsProcessEntityToMerchantOmsProcessTransfer(
        SpyMerchantOmsProcess $merchantOmsProcessEntity,
        MerchantOmsProcessTransfer $merchantOmsProcessTransfer
    ): MerchantOmsProcessTransfer {
        return $merchantOmsProcessTransfer->fromArray($merchantOmsProcessEntity->toArray(), true);
    }
}
