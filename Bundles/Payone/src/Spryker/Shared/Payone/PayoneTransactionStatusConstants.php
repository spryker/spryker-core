<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Payone;

interface PayoneTransactionStatusConstants
{

    const TXACTION_APPOINTED = 'appointed';
    const TXACTION_CAPTURE = 'capture';
    const TXACTION_PAID = 'paid';
    const TXACTION_UNDERPAID = 'underpaid';
    const TXACTION_CANCELATION = 'cancelation';
    const TXACTION_REFUND = 'refund';
    const TXACTION_DEBIT = 'debit';
    const TXACTION_REMINDER = 'reminder';
    const TXACTION_VAUTHORIZATION = 'vauthorization';
    const TXACTION_VSETTLEMENT = 'vsettlement';
    const TXACTION_TRANSFER = 'transfer';
    const TXACTION_INVOICE = 'invoice';

}
