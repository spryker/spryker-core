<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business;

use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Spryker\Shared\Payone\PayoneApiConstants;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Payone\Business\Api\Adapter\Http\Guzzle;
use Spryker\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
use Spryker\Zed\Payone\Business\ApiLog\ApiLogFinder;
use Spryker\Zed\Payone\Business\Key\HashGenerator;
use Spryker\Zed\Payone\Business\Key\HashProvider;
use Spryker\Zed\Payone\Business\Mode\ModeDetector;
use Spryker\Zed\Payone\Business\Order\OrderManager;
use Spryker\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo;
use Spryker\Zed\Payone\Business\Payment\MethodMapper\EWallet;
use Spryker\Zed\Payone\Business\Payment\MethodMapper\Invoice;
use Spryker\Zed\Payone\Business\Payment\MethodMapper\OnlineBankTransfer;
use Spryker\Zed\Payone\Business\Payment\MethodMapper\Prepayment;
use Spryker\Zed\Payone\Business\Payment\PaymentManager;
use Spryker\Zed\Payone\Business\SequenceNumber\SequenceNumberProvider;
use Spryker\Zed\Payone\Business\TransactionStatus\TransactionStatusUpdateManager;
use Spryker\Zed\Payone\PayoneDependencyProvider;

/**
 * @method \Spryker\Zed\Payone\PayoneConfig getConfig()
 * @method \Spryker\Zed\Payone\Persistence\PayoneQueryContainer getQueryContainer()
 */
class PayoneBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    private $standardParameter;

    /**
     * @return \Spryker\Zed\Payone\Business\Payment\PaymentManagerInterface
     */
    public function createPaymentManager()
    {
        $paymentManager = new PaymentManager(
            $this->createExecutionAdapter(),
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createKeyHashGenerator(),
            $this->createSequenceNumberProvider(),
            $this->createModeDetector()
        );

        foreach ($this->getAvailablePaymentMethods() as $paymentMethod) {
            $paymentManager->registerPaymentMethodMapper($paymentMethod);
        }

        return $paymentManager;
    }

    /**
     * @return \Spryker\Zed\Payone\Business\Order\OrderManagerInterface
     */
    public function createOrderManager()
    {
        $orderManager = new OrderManager($this->getConfig());

        return $orderManager;
    }

    /**
     * @return \Spryker\Zed\Payone\Business\TransactionStatus\TransactionStatusUpdateManager
     */
    public function createTransactionStatusManager()
    {
        return new TransactionStatusUpdateManager(
            $this->getQueryContainer(),
            $this->getStandardParameter(),
            $this->createKeyHashGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\Payone\Business\ApiLog\ApiLogFinder
     */
    public function createApiLogFinder()
    {
        return new ApiLogFinder(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    protected function createExecutionAdapter()
    {
        return new Guzzle(
            $this->getStandardParameter()->getPaymentGatewayUrl()
        );
    }

    /**
     * @return \Spryker\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface
     */
    protected function createSequenceNumberProvider()
    {
        $defaultEmptySequenceNumber = $this->getConfig()->getEmptySequenceNumber();

        return new SequenceNumberProvider(
            $this->getQueryContainer(),
            $defaultEmptySequenceNumber
        );
    }

    /**
     * @return \Spryker\Shared\Payone\Dependency\HashInterface
     */
    protected function createKeyHashProvider()
    {
        $hashProvider = new HashProvider();

        return $hashProvider;
    }

    /**
     * @return \Spryker\Zed\Payone\Business\Key\HashGenerator
     */
    protected function createKeyHashGenerator()
    {
        return new HashGenerator(
            $this->createHashProvider()
        );
    }

    /**
     * @return \Spryker\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected function createModeDetector()
    {
        $modeDetector = new ModeDetector($this->getConfig());

        return $modeDetector;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer
     *
     * @return \Spryker\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest
     */
    public function createTransactionStatusUpdateRequest(PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer)
    {
        return new TransactionStatusRequest($transactionStatusUpdateTransfer->toArray());
    }

    /**
     * @todo move implementation to PayoneConfig
     *
     * @return array
     */
    protected function getAvailablePaymentMethods()
    {
        $storeConfig = $this->getProvidedDependency(PayoneDependencyProvider::STORE_CONFIG);

        return [
            PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO => $this->createCreditCardPseudo($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_INVOICE => $this->createInvoice($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER => $this->createOnlineBankTransfer($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_E_WALLET => $this->createEWallet($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT => $this->createPrepayment($storeConfig),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected function getStandardParameter()
    {
        if ($this->standardParameter === null) {
            $this->standardParameter = $this->getConfig()->getRequestStandardParameter();
        }

        return $this->standardParameter;
    }

    /**
     * @return \Spryker\Zed\Payone\Business\Key\HashProvider
     */
    protected function createHashProvider()
    {
        $hashProvider = new HashProvider();

        return $hashProvider;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \Spryker\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo
     */
    protected function createCreditCardPseudo($storeConfig)
    {
        $creditCardPseudo = new CreditCardPseudo($storeConfig);

        return $creditCardPseudo;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \Spryker\Zed\Payone\Business\Payment\MethodMapper\Invoice
     */
    protected function createInvoice($storeConfig)
    {
        $invoice = new Invoice($storeConfig);

        return $invoice;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \Spryker\Zed\Payone\Business\Payment\MethodMapper\OnlineBankTransfer
     */
    protected function createOnlineBankTransfer($storeConfig)
    {
        $onlineBankTransfer = new OnlineBankTransfer($storeConfig);

        return $onlineBankTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \Spryker\Zed\Payone\Business\Payment\MethodMapper\EWallet
     */
    protected function createEWallet($storeConfig)
    {
        $EWallet = new EWallet($storeConfig);

        return $EWallet;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     *
     * @return \Spryker\Zed\Payone\Business\Payment\MethodMapper\Prepayment
     */
    protected function createPrepayment($storeConfig)
    {
        $prepayment = new Prepayment($storeConfig);

        return $prepayment;
    }

}
