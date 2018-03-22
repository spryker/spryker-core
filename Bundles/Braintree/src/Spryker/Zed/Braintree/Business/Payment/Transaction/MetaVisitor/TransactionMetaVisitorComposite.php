<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor;

use Generated\Shared\Transfer\TransactionMetaTransfer;

class TransactionMetaVisitorComposite implements TransactionMetaVisitorInterface
{
    /**
     * @var \Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\TransactionMetaVisitorInterface[]
     */
    protected $visitor = [];

    /**
     * @param \Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\TransactionMetaVisitorInterface $visitor
     *
     * @return $this
     */
    public function addVisitor(TransactionMetaVisitorInterface $visitor)
    {
        $this->visitor[] = $visitor;

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TransactionMetaTransfer $transactionMetaTransfer
     *
     * @return void
     */
    public function visit(TransactionMetaTransfer $transactionMetaTransfer)
    {
        foreach ($this->visitor as $visitor) {
            $visitor->visit($transactionMetaTransfer);
        }
    }
}
