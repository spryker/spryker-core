<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Trigger;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductEventTriggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return void
     */
    public function triggerProductUpdateEvent(ProductOfferTransfer $productOfferTransfer): void;
}
