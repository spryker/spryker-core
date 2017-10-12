<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor;

use Generated\Shared\Transfer\TransactionMetaTransfer;
use Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface;

class PaymentTransactionMetaVisitor implements TransactionMetaVisitorInterface
{
    /**
     * @var \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface $queryContainer
     */
    public function __construct(BraintreeQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\TransactionMetaTransfer $transactionMetaTransfer
     *
     * @return void
     */
    public function visit(TransactionMetaTransfer $transactionMetaTransfer)
    {
        $paymentEntity = $this->getPaymentEntity($transactionMetaTransfer);

        $transactionMetaTransfer->setIdPayment($paymentEntity->getIdPaymentBraintree());
        $transactionMetaTransfer->setTransactionIdentifier($paymentEntity->getTransactionId());
    }

    /**
     * @param \Generated\Shared\Transfer\TransactionMetaTransfer $transactionMetaTransfer
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree
     */
    protected function getPaymentEntity(TransactionMetaTransfer $transactionMetaTransfer)
    {
        $idSalesOrderEntity = $transactionMetaTransfer->requireIdSalesOrder()->getIdSalesOrder();

        return $this->queryContainer->queryPaymentBySalesOrderId($idSalesOrderEntity)->findOne();
    }
}
