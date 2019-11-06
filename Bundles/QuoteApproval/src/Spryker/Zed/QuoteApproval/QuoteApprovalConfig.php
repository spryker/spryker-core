<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\QuoteApproval\QuoteApprovalConfig getSharedConfig()
 */
class QuoteApprovalConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getRequiredQuoteFieldsForApprovalProcess(): array
    {
        return $this->getSharedConfig()->getRequiredQuoteFieldsForApprovalProcess();
    }

    /**
     * @deprecated Will be removed without replacement. BC-reason only.
     *
     * @return bool
     */
    public function isShipmentPriceIncludedInQuoteApprovalPermissionCheck(): bool
    {
        return $this->getSharedConfig()->isShipmentPriceIncludedInQuoteApprovalPermissionCheck();
    }
}
