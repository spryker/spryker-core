<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business\Mapper;

use Generated\Shared\Transfer\MerchantSearchTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantSearchMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer
     *
     * @return void
     */
    public function mapMerchantTransferToMerchantSearchTransfer(
        MerchantTransfer $merchantTransfer,
        MerchantSearchTransfer $merchantSearchTransfer
    ): void;
}
