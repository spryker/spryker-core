<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business;

use SprykerFeature\Zed\Payone\Business\Payment\MethodMapper\Prepayment;
use SprykerFeature\Zed\Payone\Business\Payment\MethodMapper\EWallet;
use SprykerFeature\Zed\Payone\Business\Payment\MethodMapper\OnlineBankTransfer;
use SprykerFeature\Zed\Payone\Business\Payment\MethodMapper\Invoice;
use SprykerFeature\Zed\Payone\Business\Payment\MethodMapper\CreditCardPseudo;
use SprykerFeature\Zed\Payone\Business\Mode\ModeDetector;
use SprykerFeature\Zed\Payone\Business\Key\HashProvider;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProvider;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\Http\Guzzle;
use SprykerFeature\Zed\Payone\Business\Order\OrderManager;
use SprykerFeature\Zed\Payone\Business\Payment\PaymentManager;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
use SprykerFeature\Zed\Payone\Business\Key\HashGenerator;
use SprykerFeature\Zed\Payone\Business\Payment\PaymentManagerInterface;
use SprykerFeature\Zed\Payone\Business\Order\OrderManagerInterface;
use SprykerFeature\Zed\Payone\Business\TransactionStatus\TransactionStatusUpdateManager;
use SprykerFeature\Zed\Payone\PayoneConfig;
use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerFeature\Shared\Payone\Dependency\HashInterface;
use SprykerFeature\Zed\Payone\PayoneDependencyProvider;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainer;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use SprykerFeature\Zed\Payone\Business\ApiLog\ApiLogFinder;

/**
 * @method PayoneConfig getConfig()
 */
class PayoneDependencyContainer extends AbstractBusinessDependencyContainer
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
                $this->createQueryContainer(),
                $this->createStandardParameter(),
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
        return new OrderManager($this->getConfig());
    }

    /**
     * @return TransactionStatusUpdateManager
     */
    public function createTransactionStatusManager()
    {
        return new TransactionStatusUpdateManager(
                $this->createQueryContainer(),
                $this->createStandardParameter(),
                $this->createKeyHashGenerator()
            );
    }

    /**
     * @return ApiLogFinder
     */
    public function createApiLogFinder()
    {
        return new ApiLogFinder(
            $this->createQueryContainer()
        );
    }

    /**
     * @return PayoneQueryContainer
     */
    protected function createQueryContainer()
    {
        return $this->getLocator()->payone()->queryContainer();
    }

    /**
     * @return AdapterInterface
     */
    protected function createExecutionAdapter()
    {
        return new Guzzle(
                $this->createStandardParameter()->getPaymentGatewayUrl()
            );
    }

    /**
     * @return SequenceNumberProviderInterface
     */
    protected function createSequenceNumberProvider()
    {
        $defaultEmptySequenceNumber = $this->getConfig()->getEmptySequenceNumber();

        return new SequenceNumberProvider(
                $this->createQueryContainer(),
                $defaultEmptySequenceNumber
            );
    }

    /**
     * @return HashInterface
     */
    protected function createKeyHashProvider()
    {
        return new HashProvider();
    }

    /**
     * @return HashGenerator
     */
    protected function createKeyHashGenerator()
    {
        return new HashGenerator(new HashProvider());
    }

    /**
     * @return ModeDetectorInterface
     */
    protected function createModeDetector()
    {
        return new ModeDetector($this->getConfig());
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
            PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO => new CreditCardPseudo($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_INVOICE => new Invoice($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER => new OnlineBankTransfer($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_E_WALLET => new EWallet($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT => new Prepayment($storeConfig),
        ];
    }

    /**
     * @return PayoneStandardParameterTransfer
     */
    protected function createStandardParameter()
    {
        if ($this->standardParameter === null) {
            $this->standardParameter = $this->getConfig()->getRequestStandardParameter();
        }

        return $this->standardParameter;
    }

}
