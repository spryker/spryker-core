<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Ratepay\Business\Api\Adapter\Http\Guzzle;
use Spryker\Zed\Ratepay\Business\Api\ApiFactory;
use Spryker\Zed\Ratepay\Business\Api\Builder\BuilderFactory;
use Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory;
use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;
use Spryker\Zed\Ratepay\Business\Expander\ProductExpander;
use Spryker\Zed\Ratepay\Business\Internal\Install;
use Spryker\Zed\Ratepay\Business\Order\MethodMapperFactory;
use Spryker\Zed\Ratepay\Business\Order\Saver;
use Spryker\Zed\Ratepay\Business\Payment\PaymentSaver;
use Spryker\Zed\Ratepay\Business\Payment\PostSaveHook;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\CancelPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\ConfirmDeliveryTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\ConfirmPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InitPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InstallmentCalculationTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InstallmentConfigurationTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\RefundPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\RequestPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Method\Elv;
use Spryker\Zed\Ratepay\Business\Request\Payment\Method\Installment;
use Spryker\Zed\Ratepay\Business\Request\Payment\Method\Invoice;
use Spryker\Zed\Ratepay\Business\Request\Payment\Method\Prepayment;
use Spryker\Zed\Ratepay\Business\Request\Service\Handler\Transaction\ProfileTransaction;
use Spryker\Zed\Ratepay\Business\Request\Service\Method\Service;
use Spryker\Zed\Ratepay\Business\Status\TransactionStatus;
use Spryker\Zed\Ratepay\RatepayDependencyProvider;

/**
 * @method \Spryker\Zed\Ratepay\Persistence\RatepayQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Ratepay\RatepayConfig getConfig()
 */
class RatepayBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @param string $gatewayUrl
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface
     */
    protected function createAdapter($gatewayUrl)
    {
        return new Guzzle($gatewayUrl);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Service\Handler\Transaction\ProfileTransaction
     */
    public function createRequestProfileTransactionHandler()
    {
        $transactionHandler = new ProfileTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->getQueryContainer()
        );

        $transactionHandler->registerMethodMapper($this->createProfile($this->createRequestTransfer()));

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InitPaymentTransaction
     */
    public function createInitPaymentTransactionHandler()
    {
        $transactionHandler = new InitPaymentTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\RequestPaymentTransaction
     */
    public function createRequestPaymentTransactionHandler()
    {
        $transactionHandler = new RequestPaymentTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\PaymentSaverInterface
     */
    public function createPaymentMethodSaver()
    {
        $paymentSaver = new PaymentSaver(
            $this->getQueryContainer()
        );

        return $paymentSaver;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\ConfirmPaymentTransaction
     */
    public function createConfirmPaymentTransactionHandler()
    {
        $transactionHandler = new ConfirmPaymentTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\ConfirmDeliveryTransaction
     */
    public function createConfirmDeliveryTransactionHandler()
    {
        $transactionHandler = new ConfirmDeliveryTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\CancelPaymentTransaction
     */
    public function createCancelPaymentTransactionHandler()
    {
        $transactionHandler = new CancelPaymentTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\RefundPaymentTransaction
     */
    public function createRefundPaymentTransactionHandler()
    {
        $transactionHandler = new RefundPaymentTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->getQueryContainer()
        );

        $this->registerAllMethodMappers($transactionHandler);

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InstallmentConfigurationTransaction
     */
    public function createInstallmentConfigurationTransactionHandler()
    {
        $transactionHandler = new InstallmentConfigurationTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->getQueryContainer()
        );

        $transactionHandler->registerMethodMapper($this->createInstallment($this->createRequestTransfer()));

        return $transactionHandler;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InstallmentCalculationTransaction
     */
    public function createInstallmentCalculationTransactionHandler()
    {
        $transactionHandler = new InstallmentCalculationTransaction(
            $this->createAdapter($this->getConfig()->getTransactionGatewayUrl()),
            $this->createConverterFactory(),
            $this->getQueryContainer()
        );

        $transactionHandler->registerMethodMapper($this->createInstallment($this->createRequestTransfer()));

        return $transactionHandler;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\QuoteTransactionInterface|\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\OrderTransactionInterface|\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\PaymentInitTransactionInterface $transactionHandler
     *
     * @return void
     */
    protected function registerAllMethodMappers($transactionHandler)
    {
        $requestTransfer = $this->createRequestTransfer();

        $transactionHandler->registerMethodMapper($this->createInvoice($requestTransfer));
        $transactionHandler->registerMethodMapper($this->createElv($requestTransfer));
        $transactionHandler->registerMethodMapper($this->createPrepayment($requestTransfer));
        $transactionHandler->registerMethodMapper($this->createInstallment($requestTransfer));
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Status\TransactionStatus
     */
    public function createStatusTransaction()
    {
        return new TransactionStatus(
            $this->getQueryContainer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Model\RequestModelFactoryInterface
     */
    public function createApiRequestFactory($requestTransfer)
    {
        $factory = new ApiFactory(
            $this->createBuilderFactory($requestTransfer)
        );

        return $factory->createRequestModelFactory();
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Order\MethodMapperFactory
     */
    public function getMethodMapperFactory()
    {
        return new MethodMapperFactory();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Order\SaverInterface
     */
    public function createOrderSaver(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ) {
        $paymentMapper = $this
            ->getMethodMapperFactory()
            ->createPaymentTransactionHandler()
            ->prepareMethodMapper($quoteTransfer);

        return new Saver(
            $quoteTransfer,
            $checkoutResponseTransfer,
            $paymentMapper
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Converter\ConverterFactory
     */
    protected function createConverterFactory()
    {
        return new ConverterFactory(
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface
     */
    protected function getMoneyFacade()
    {
        return $this->getProvidedDependency(RatepayDependencyProvider::FACADE_MONEY);
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory
     */
    protected function createMapperFactory(RatepayRequestTransfer $requestTransfer)
    {
        return new MapperFactory(
            $requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\BuilderFactory
     */
    protected function createBuilderFactory(RatepayRequestTransfer $requestTransfer)
    {
        return new BuilderFactory(
            $requestTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Method\Invoice
     */
    public function createInvoice(RatepayRequestTransfer $requestTransfer)
    {
        return new Invoice(
            $this->createApiRequestFactory($requestTransfer),
            $this->createMapperFactory($requestTransfer),
            $this->getQueryContainer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Method\Elv
     */
    public function createElv(RatepayRequestTransfer $requestTransfer)
    {
        return new Elv(
            $this->createApiRequestFactory($requestTransfer),
            $this->createMapperFactory($requestTransfer),
            $this->getQueryContainer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Method\Prepayment
     */
    public function createPrepayment(RatepayRequestTransfer $requestTransfer)
    {
        return new Prepayment(
            $this->createApiRequestFactory($requestTransfer),
            $this->createMapperFactory($requestTransfer),
            $this->getQueryContainer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Request\Payment\Method\Installment
     */
    public function createInstallment(RatepayRequestTransfer $requestTransfer)
    {
        return new Installment(
            $this->createApiRequestFactory($requestTransfer),
            $this->createMapperFactory($requestTransfer),
            $this->getQueryContainer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayRequestTransfer $requestTransfer
     *
     * @return \Spryker\Zed\Ratepay\Business\Request\Service\Method\Service
     */
    public function createProfile(RatepayRequestTransfer $requestTransfer)
    {
        return new Service(
            $this->createApiRequestFactory($requestTransfer),
            $this->createMapperFactory($requestTransfer),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Expander\ProductExpanderInterface
     */
    public function createProductExpander()
    {
        return new ProductExpander(
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(RatepayDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Internal\Install
     */
    public function createInstaller()
    {
        $installer = new Install(
            $this->getGlossaryFacade(),
            $this->getConfig()
        );

        return $installer;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Payment\PostSaveHookInterface
     */
    public function createPostSaveHook()
    {
        return new PostSaveHook(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToGlossaryInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(RatepayDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected function createRequestTransfer()
    {
        return new RatepayRequestTransfer();
    }
}
