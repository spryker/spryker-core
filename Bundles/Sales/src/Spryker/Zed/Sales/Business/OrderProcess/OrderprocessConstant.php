<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\OrderProcess;

interface OrderprocessConstant
{
    // STATE
    public const STATE_NEW = 'new';

    public const STATE_EXPORTABLE = 'exportable';

    public const STATE_CLOSED = 'closed';

    public const STATE_FINALLY_CLOSED = 'finally closed';

    public const STATE_WAITING_FOR_PAYMENT = 'waiting for payment';

    // EVENT
    public const EVENT_ON_ENTER = 'onEnter';

    public const EVENT_CLOSE = 'close';

    public const EVENT_START_INVOICE_CREATION = 'start invoice creation';

    public const EVENT_CHECK_DAILY_TIMEOUT = 'check daily timeout';

    public const EVENT_CHECK_WEEKLY_TIMEOUT = 'check weekly timeout';

    public const EVENT_CHECK_MONTHLY_TIMEOUT = 'check monthly timeout';

    public const EVENT_REDIRECT_PAYMENT_CANCELLED_REGULAR = 'redirect payment cancelled regular';

    // CONDITION
    public const RULE_ORDER_USED_CODE = 'order used code';
}
