<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business;

use Spryker\Zed\Payone\Business\Payment\MethodMapper\Prepayment;
use Spryker\Zed\Payone\Business\Payment\MethodMapper\EWallet;
use Spryker\Zed\Payone\Business\Payment\MethodMapper\OnlineBankTransfer;
use Spryker\Zed\Payone\Business\Payment\MethodMapper\Invoice;
use Spryker\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo;
use Spryker\Zed\Payone\Business\Mode\ModeDetector;
use Spryker\Zed\Payone\Business\Key\HashProvider;
use Spryker\Zed\Payone\Business\SequenceNumber\SequenceNumberProvider;
use Spryker\Zed\Payone\Business\Api\Adapter\Http\Guzzle;
use Spryker\Zed\Payone\Business\Order\OrderManager;
use Spryker\Zed\Payone\Business\Payment\PaymentManager;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Spryker\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use Spryker\Shared\Payone\PayoneApiConstants;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
use Spryker\Zed\Payone\Business\Key\HashGenerator;
use Spryker\Zed\Payone\Business\Payment\PaymentManagerInterface;
use Spryker\Zed\Payone\Business\Order\OrderManagerInterface;
use Spryker\Zed\Payone\Business\TransactionStatus\TransactionStatusUpdateManager;
use Spryker\Zed\Payone\PayoneConfig;
use Spryker\Shared\Payone\Dependency\ModeDetectorInterface;
use Spryker\Shared\Payone\Dependency\HashInterface;
use Spryker\Zed\Payone\PayoneDependencyProvider;
use Spryker\Zed\Payone\Persistence\PayoneQueryContainer;
use Spryker\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use Spryker\Zed\Payone\Business\ApiLog\ApiLogFinder;

/**
 * @method PayoneConfig getConfig()
 * @method PayoneQueryContainer getQueryContainer()
 */
class PayoneBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @var PayoneStandardParameterTransfer
     */
    private $standardParameter;

    /**
     * @return PaymentManagerInterface
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
     * @return OrderManagerInterface
     */
    public function createOrderManager()
    {
        $orderManager = new OrderManager($this->getConfig());

        return $orderManager;
    }

    /**
     * @return TransactionStatusUpdateManager
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
     * @return ApiLogFinder
     */
    public function createApiLogFinder()
    {
        return new ApiLogFinder(
            $this->getQueryContainer()
        );
    }

    /**
     * @return AdapterInterface
     */
    protected function createExecutionAdapter()
    {
        return new Guzzle(
            $this->getStandardParameter()->getPaymentGatewayUrl()
        );
    }

    /**
     * @return SequenceNumberProviderInterface
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
     * @return HashInterface
     */
    protected function createKeyHashProvider()
    {
        $hashProvider = new HashProvider();

        return $hashProvider;
    }

    /**
     * @return HashGenerator
     */
    protected function createKeyHashGenerator()
    {
        return new HashGenerator(
            $this->createHashProvider()
        );
    }

    /**
     * @return ModeDetectorInterface
     */
    protected function createModeDetector()
    {
        $modeDetector = new ModeDetector($this->getConfig());

        return $modeDetector;
    }

    /**
     * @param PayoneTransactionStatusUpdateTransfer $transactionStatusUpdateTransfer
     *
     * @return TransactionStatusRequest
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
     * @deprecated, Use getStandardParameter() instead.
     *
     * @return PayoneStandardParameterTransfer
     */
    protected function createStandardParameter()
    {
        trigger_error('Deprecated, use getStandardParameter() instead.', E_USER_DEPRECATED);

        return $this->getStandardParameter();
    }

    /**
     * @return PayoneStandardParameterTransfer
     */
    protected function getStandardParameter()
    {
        if ($this->standardParameter === null) {
            $this->standardParameter = $this->getConfig()->getRequestStandardParameter();
        }

        return $this->standardParameter;
    }

    /**
     * @return HashProvider
     */
    protected function createHashProvider()
    {
        $hashProvider = new HashProvider();

        return $hashProvider;
    }

    /**
     * @param $storeConfig
     *
     * @return CreditCardPseudo
     */
    protected function createCreditCardPseudo($storeConfig)
    {
        $creditCardPseudo = new CreditCardPseudo($storeConfig);

        return $creditCardPseudo;
    }

    /**
     * @param $storeConfig
     *
     * @return Invoice
     */
    protected function createInvoice($storeConfig)
    {
        $invoice = new Invoice($storeConfig);

        return $invoice;
    }

    /**
     * @param $storeConfig
     *
     * @return OnlineBankTransfer
     */
    protected function createOnlineBankTransfer($storeConfig)
    {
        $onlineBankTransfer = new OnlineBankTransfer($storeConfig);

        return $onlineBankTransfer;
    }

    /**
     * @param $storeConfig
     *
     * @return EWallet
     */
    protected function createEWallet($storeConfig)
    {
        $EWallet = new EWallet($storeConfig);

        return $EWallet;
    }

    /**
     * @param $storeConfig
     *
     * @return Prepayment
     */
    protected function createPrepayment($storeConfig)
    {
        $prepayment = new Prepayment($storeConfig);

        return $prepayment;
    }

}
