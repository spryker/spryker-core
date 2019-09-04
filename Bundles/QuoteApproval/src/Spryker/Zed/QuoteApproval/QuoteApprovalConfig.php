<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class QuoteApprovalConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getRequiredQuoteFields(): array
    {
        return [];
    }
}
