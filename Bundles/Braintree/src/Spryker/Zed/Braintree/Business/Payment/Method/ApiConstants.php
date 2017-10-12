<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Method;

interface ApiConstants
{
    const METHOD_PAY_PAL = 'BRAINTREE_PAY_PAL';
    const METHOD_CREDIT_CARD = 'BRAINTREE_CREDIT_CARD';

    const TRANSACTION_MODE = 'TRANSACTION.MODE';
    const TRANSACTION_MODE_TEST = 'CONNECTOR_TEST';
    const TRANSACTION_MODE_LIVE = 'LIVE';

    const SALE = 'sale';
    const CREDIT = 'credit';

    const TRANSACTION_CODE_AUTHORIZE = 'authorize';
    const TRANSACTION_CODE_CAPTURE = 'capture';
    const TRANSACTION_CODE_REVERSAL = 'reversal';
    const TRANSACTION_CODE_REFUND = 'refund';

    const STATUS_CODE_PRE_CHECK = 'authorized';
    const STATUS_CODE_AUTHORIZE = 'authorized';
    const STATUS_CODE_CAPTURE = 'settling'; // Braintree\Transaction::SETTLEMENT_CONFIRMED
    const STATUS_CODE_CAPTURE_SUBMITTED = 'submitted_for_settlement';
    const STATUS_CODE_REVERSAL = 'voided';
    const STATUS_CODE_REFUND = 'settling';

    const PAYMENT_CODE_AUTHORIZE_SUCCESS = '1000';
    const STATUS_REASON_CODE_SUCCESS = '1';
}
