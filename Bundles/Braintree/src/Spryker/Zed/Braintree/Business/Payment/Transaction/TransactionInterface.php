<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Transaction;

use Generated\Shared\Transfer\TransactionMetaTransfer;

interface TransactionInterface
{
    /**
     * @param \Generated\Shared\Transfer\TransactionMetaTransfer $transactionMetaTransfer
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function executeTransaction(TransactionMetaTransfer $transactionMetaTransfer);
}
