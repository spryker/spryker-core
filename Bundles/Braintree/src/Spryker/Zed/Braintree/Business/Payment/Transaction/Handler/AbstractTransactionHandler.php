<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Transaction\Handler;

use Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\TransactionMetaVisitorInterface;
use Spryker\Zed\Braintree\Business\Payment\Transaction\TransactionInterface;

abstract class AbstractTransactionHandler
{
    /**
     * @var \Spryker\Zed\Braintree\Business\Payment\Transaction\TransactionInterface
     */
    protected $transaction;

    /**
     * @var \Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\TransactionMetaVisitorInterface
     */
    protected $transactionMetaVisitor;

    /**
     * @param \Spryker\Zed\Braintree\Business\Payment\Transaction\TransactionInterface $transaction
     * @param \Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\TransactionMetaVisitorInterface $transactionMetaVisitor
     */
    public function __construct(TransactionInterface $transaction, TransactionMetaVisitorInterface $transactionMetaVisitor)
    {
        $this->transaction = $transaction;
        $this->transactionMetaVisitor = $transactionMetaVisitor;
    }
}
