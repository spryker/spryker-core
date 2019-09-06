<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\QuoteApproval;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class QuoteApprovalConfig extends AbstractSharedConfig
{
    public const STATUS_WAITING = 'waiting';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DECLINED = 'declined';

    public const PERMISSION_CONTEXT_CENT_AMOUNT = 'PERMISSION_CONTEXT_CENT_AMOUNT';
    public const PERMISSION_CONTEXT_STORE_NAME = 'PERMISSION_CONTEXT_STORE_NAME';
    public const PERMISSION_CONTEXT_CURRENCY_CODE = 'PERMISSION_CONTEXT_CURRENCY_CODE';

    /**
     * @return array
     */
    public function getRequiredQuoteFields(): array
    {
        return [];
    }
}
