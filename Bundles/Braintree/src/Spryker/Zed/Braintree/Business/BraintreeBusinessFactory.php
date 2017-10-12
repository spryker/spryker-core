<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business;

use Spryker\Zed\Braintree\BraintreeDependencyProvider;
use Spryker\Zed\Braintree\Business\Hook\PostSaveHook;
use Spryker\Zed\Braintree\Business\Log\TransactionStatusLog;
use Spryker\Zed\Braintree\Business\Order\Saver;
use Spryker\Zed\Braintree\Business\Payment\Transaction\AuthorizeTransaction;
use Spryker\Zed\Braintree\Business\Payment\Transaction\CaptureTransaction;
use Spryker\Zed\Braintree\Business\Payment\Transaction\Handler\AuthorizeTransactionHandler;
use Spryker\Zed\Braintree\Business\Payment\Transaction\Handler\CaptureTransactionHandler;
use Spryker\Zed\Braintree\Business\Payment\Transaction\Handler\PreCheckTransactionHandler;
use Spryker\Zed\Braintree\Business\Payment\Transaction\Handler\RefundTransactionHandler;
use Spryker\Zed\Braintree\Business\Payment\Transaction\Handler\RevertTransactionHandler;
use Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\PaymentTransactionMetaVisitor;
use Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\TransactionMetaVisitorComposite;
use Spryker\Zed\Braintree\Business\Payment\Transaction\PreCheckTransaction;
use Spryker\Zed\Braintree\Business\Payment\Transaction\RefundTransaction;
use Spryker\Zed\Braintree\Business\Payment\Transaction\RevertTransaction;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Braintree\BraintreeConfig getConfig()
 */
class BraintreeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\Handler\AuthorizeTransactionHandler
     */
    public function createAuthorizeTransactionHandler()
    {
        return new AuthorizeTransactionHandler(
            $this->createAuthorizeTransaction(),
            $this->createDefaultTransactionMetaVisitor()
        );
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\AuthorizeTransaction
     */
    protected function createAuthorizeTransaction()
    {
        return new AuthorizeTransaction($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\TransactionMetaVisitorComposite
     */
    protected function createDefaultTransactionMetaVisitor()
    {
        $transactionMetaVisitorComposite = $this->createTransactionMetaVisitorComposite();
        $transactionMetaVisitorComposite->addVisitor($this->createPaymentTransactionMetaVisitor());

        return $transactionMetaVisitorComposite;
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\TransactionMetaVisitorComposite
     */
    protected function createTransactionMetaVisitorComposite()
    {
        return new TransactionMetaVisitorComposite();
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\MetaVisitor\PaymentTransactionMetaVisitor
     */
    protected function createPaymentTransactionMetaVisitor()
    {
        return new PaymentTransactionMetaVisitor($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\Handler\CaptureTransactionHandler
     */
    public function createCaptureTransactionHandler()
    {
        return new CaptureTransactionHandler(
            $this->createCaptureTransaction(),
            $this->createDefaultTransactionMetaVisitor()
        );
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\CaptureTransaction
     */
    protected function createCaptureTransaction()
    {
        return new CaptureTransaction($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\Handler\PreCheckTransactionHandler
     */
    public function createPreCheckTransactionHandler()
    {
        return new PreCheckTransactionHandler(
            $this->createPreCheckTransaction(),
            $this->createDefaultTransactionMetaVisitor()
        );
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\PreCheckTransaction
     */
    protected function createPreCheckTransaction()
    {
        return new PreCheckTransaction($this->getConfig(), $this->getMoneyFacade());
    }

    /**
     * @return \Spryker\Zed\Braintree\Dependency\Facade\BraintreeToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(BraintreeDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\Handler\RefundTransactionHandler
     */
    public function createRefundTransactionHandler()
    {
        return new RefundTransactionHandler(
            $this->createRefundTransaction(),
            $this->createDefaultTransactionMetaVisitor(),
            $this->getRefundFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\RefundTransaction
     */
    protected function createRefundTransaction()
    {
        return new RefundTransaction($this->getConfig(), $this->getMoneyFacade());
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\Handler\RevertTransactionHandler
     */
    public function createRevertTransactionHandler()
    {
        return new RevertTransactionHandler(
            $this->createRevertTransaction(),
            $this->createDefaultTransactionMetaVisitor()
        );
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Payment\Transaction\RevertTransaction
     */
    protected function createRevertTransaction()
    {
        return new RevertTransaction($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Braintree\Dependency\Facade\BraintreeToRefundInterface
     */
    protected function getRefundFacade()
    {
        return $this->getProvidedDependency(BraintreeDependencyProvider::FACADE_REFUND);
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Order\SaverInterface
     */
    public function createOrderSaver()
    {
        return new Saver();
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Log\TransactionStatusLogInterface
     */
    public function createTransactionStatusLog()
    {
        return new TransactionStatusLog($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Braintree\Business\Hook\PostSaveHookInterface
     */
    public function createPostSaveHook()
    {
        return new PostSaveHook($this->getQueryContainer());
    }
}
