<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcher;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\MerchantSwitchResponseTransfer;

interface MerchantSwitcherInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInQuote(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchantInQuoteItems(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer;
}
