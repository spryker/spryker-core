<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Transaction\Handler;

use Generated\Shared\Transfer\TransactionMetaTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\TransactionMetaVisitorInterface;
use Spryker\Zed\Braintree\Business\Payment\Transaction\TransactionInterface;
use Spryker\Zed\Braintree\Dependency\Facade\BraintreeToRefundInterface;

class RefundTransactionHandler extends AbstractTransactionHandler
{
    /**
     * @var \Spryker\Zed\Braintree\Dependency\Facade\BraintreeToRefundInterface
     */
    protected $refundFacade;

    /**
     * @param \Spryker\Zed\Braintree\Business\Payment\Transaction\TransactionInterface $transaction
     * @param \Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\TransactionMetaVisitorInterface $transactionMetaVisitor
     * @param \Spryker\Zed\Braintree\Dependency\Facade\BraintreeToRefundInterface $refundFacade
     */
    public function __construct(TransactionInterface $transaction, TransactionMetaVisitorInterface $transactionMetaVisitor, BraintreeToRefundInterface $refundFacade)
    {
        parent::__construct($transaction, $transactionMetaVisitor);

        $this->refundFacade = $refundFacade;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\BraintreeTransactionResponseTransfer
     */
    public function refund(array $salesOrderItems, SpySalesOrder $salesOrderEntity)
    {
        $refundTransfer = $this->getRefund($salesOrderItems, $salesOrderEntity);

        $transactionMetaTransfer = new TransactionMetaTransfer();
        $transactionMetaTransfer->setIdSalesOrder($salesOrderEntity->getIdSalesOrder());
        $transactionMetaTransfer->setRefund($this->getRefund($salesOrderItems, $salesOrderEntity));

        $this->transactionMetaVisitor->visit($transactionMetaTransfer);

        $braintreeTransactionResponseTransfer = $this->transaction->executeTransaction($transactionMetaTransfer);

        if ($braintreeTransactionResponseTransfer->getIsSuccess()) {
            $this->refundFacade->saveRefund($refundTransfer);
        }

        return $braintreeTransactionResponseTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    protected function getRefund(array $salesOrderItems, SpySalesOrder $salesOrderEntity)
    {
        return $this->refundFacade->calculateRefund($salesOrderItems, $salesOrderEntity);
    }
}
