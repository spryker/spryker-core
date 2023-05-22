<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Trigger;

use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantEventTriggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function triggerMerchantCreatedEvent(MerchantTransfer $merchantTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function triggerMerchantUpdatedEvent(MerchantTransfer $merchantTransfer): void;
}
