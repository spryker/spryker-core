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
}
