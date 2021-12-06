<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Updater;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;

interface DiscountStoreUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return void
     */
    public function updateDiscountStoreRelationships(DiscountConfiguratorTransfer $discountConfiguratorTransfer): void;
}
