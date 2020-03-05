<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business\MerchantUserResolver;

use Generated\Shared\Transfer\MerchantUserTransfer;

interface MerchantUserResolverInterface
{
    /**
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */
    public function findCurrentMerchantUser(): ?MerchantUserTransfer;
}
