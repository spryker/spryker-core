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
    public function getRequiredQuoteFields(): array
    {
        return $this->getSharedConfig()->getRequiredQuoteFields();
    }
}
