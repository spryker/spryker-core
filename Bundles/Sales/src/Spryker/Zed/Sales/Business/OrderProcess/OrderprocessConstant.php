<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\OrderProcess;

interface OrderprocessConstant
{
    // STATE
    /**
     * @var string
     */
    public const STATE_NEW = 'new';

    /**
     * @var string
     */
    public const STATE_EXPORTABLE = 'exportable';

    /**
     * @var string
     */
    public const STATE_CLOSED = 'closed';

    /**
     * @var string
     */
    public const STATE_FINALLY_CLOSED = 'finally closed';

    /**
     * @var string
     */
    public const STATE_WAITING_FOR_PAYMENT = 'waiting for payment';

    // EVENT
    /**
     * @var string
     */
    public const EVENT_ON_ENTER = 'onEnter';

    /**
     * @var string
     */
    public const EVENT_CLOSE = 'close';

    /**
     * @var string
     */
    public const EVENT_START_INVOICE_CREATION = 'start invoice creation';

    /**
     * @var string
     */
    public const EVENT_CHECK_DAILY_TIMEOUT = 'check daily timeout';

    /**
     * @var string
     */
    public const EVENT_CHECK_WEEKLY_TIMEOUT = 'check weekly timeout';

    /**
     * @var string
     */
    public const EVENT_CHECK_MONTHLY_TIMEOUT = 'check monthly timeout';

    /**
     * @var string
     */
    public const EVENT_REDIRECT_PAYMENT_CANCELLED_REGULAR = 'redirect payment cancelled regular';

    // CONDITION
    /**
     * @var string
     */
    public const RULE_ORDER_USED_CODE = 'order used code';
}
