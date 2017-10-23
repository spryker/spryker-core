<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Order;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Elv as ElvMapper;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Installment as InstallmentMapper;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Invoice as InvoiceMapper;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Prepayment as PrepaymentMapper;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\Transaction\Transaction;

class MethodMapperFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Ratepay\Business\Order\MethodMapper\Transaction\TransactionInterface
     */
    public function createPaymentTransactionHandler()
    {
        $paymentTransactionHandler = new Transaction();
        $paymentTransactionHandler->registerMethodMapper($this->createElvMethodMapper());
        $paymentTransactionHandler->registerMethodMapper($this->createInstallmentMethodMapper());
        $paymentTransactionHandler->registerMethodMapper($this->createInvoiceMethodMapper());
        $paymentTransactionHandler->registerMethodMapper($this->createPrepaymentMethodMapper());

        return $paymentTransactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Order\MethodMapper\Elv
     */
    protected function createElvMethodMapper()
    {
        return new ElvMapper();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Order\MethodMapper\Invoice
     */
    protected function createInvoiceMethodMapper()
    {
        return new InvoiceMapper();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Order\MethodMapper\Prepayment
     */
    protected function createPrepaymentMethodMapper()
    {
        return new PrepaymentMapper();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Order\MethodMapper\Installment
     */
    protected function createInstallmentMethodMapper()
    {
        return new InstallmentMapper();
    }
}
