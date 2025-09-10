<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Event;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;

interface ProductEventTriggerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return void
     */
    public function trigger(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): void;
}
