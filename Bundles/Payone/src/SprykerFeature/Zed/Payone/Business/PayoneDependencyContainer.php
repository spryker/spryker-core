<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business;

use Generated\Shared\Payone\PayoneStandardParameterInterface;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerEngine\Zed\Kernel\Business\Factory;
use Generated\Zed\Ide\FactoryAutoCompletion\PayoneBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
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
 * @method Factory|PayoneBusiness getFactory()
 * @method PayoneConfig getConfig()
 */
class PayoneDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @var PayoneStandardParameterInterface
     */
    private $standardParameter;

    /**
     * @return PayoneFacade
     */
    public function createPayoneFacade()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return PaymentManagerInterface
     */
    public function createPaymentManager()
    {
        $paymentManager = $this->getFactory()
            ->createPaymentPaymentManager(
                $this->createExecutionAdapter(),
                $this->createQueryContainer(),
                $this->createStandardParameter(),
                $this->createKeyHashProvider(),
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
        return $this->getFactory()->createOrderOrderManager($this->getConfig());
    }

    /**
     * @return TransactionStatusUpdateManager
     */
    public function createTransactionStatusManager()
    {
        return $this->getFactory()
            ->createTransactionStatusTransactionStatusUpdateManager(
                $this->createQueryContainer(),
                $this->createStandardParameter(),
                $this->createKeyHashProvider()
            );
    }

    /**
     * @return ApiLogFinder
     */
    public function createApiLogFinder()
    {
        return $this->getFactory()->createApiLogApiLogFinder(
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
        return $this->getFactory()
            ->createApiAdapterHttpCurl(
                $this->createStandardParameter()->getPaymentGatewayUrl()
            );
    }

    /**
     * @return SequenceNumberProviderInterface
     */
    protected function createSequenceNumberProvider()
    {
        return $this->getFactory()
            ->createSequenceNumberSequenceNumberProvider(
                $this->createQueryContainer()
            );
    }

    /**
     * @return HashInterface
     */
    protected function createKeyHashProvider()
    {
        return $this->getFactory()->createKeyHashProvider();
    }

    /**
     * @return ModeDetectorInterface
     */
    protected function createModeDetector()
    {
        return $this->getFactory()->createModeModeDetector();
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
            PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO => $this->getFactory()->createPaymentMethodMapperCreditCardPseudo($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_INVOICE => $this->getFactory()->createPaymentMethodMapperInvoice($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_ONLINE_BANK_TRANSFER => $this->getFactory()->createPaymentMethodMapperOnlineBankTransfer($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_E_WALLET => $this->getFactory()->createPaymentMethodMapperEWallet($storeConfig),
            PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT => $this->getFactory()->createPaymentMethodMapperPrepayment($storeConfig),
        ];
    }

    /**
     * @return PayoneStandardParameterInterface
     */
    protected function createStandardParameter()
    {
        if ($this->standardParameter === null) {
            $this->standardParameter = $this->getConfig()->getRequestStandardParameter();
        }

        return $this->standardParameter;
    }

}
