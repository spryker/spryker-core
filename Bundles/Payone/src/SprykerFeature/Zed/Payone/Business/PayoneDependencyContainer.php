<?php

namespace SprykerFeature\Zed\Payone\Business;

use SprykerFeature\Shared\Payone\Dependency\StandardParameterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerEngine\Zed\Kernel\Business\Factory;
use Generated\Zed\Ide\FactoryAutoCompletion\PayoneBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
use SprykerFeature\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerFeature\Zed\Payone\Business\Payment\PaymentManager;
use SprykerFeature\Zed\Payone\Business\TransactionStatus\TransactionStatusUpdateManager;
use SprykerFeature\Zed\Payone\PayoneConfig;

/**
 * @method Factory|PayoneBusiness getFactory()
 * @method PayoneConfig getConfig()
 */
class PayoneDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @var StandardParameterInterface
     */
    private $standardParameter;


    /**
     * @return PaymentManager
     */
    public function createPaymentManager()
    {
        $paymentManager = $this->getFactory()
            ->createPaymentPaymentManager(
                $this->getLocator(),
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
     * @return PayoneFacade
     */
    public function createPayoneFacade()
    {
        return $this->getLocator()->payone()->facade();
    }

    /**
     * @return \SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainer
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
     * @return SequenceNumber\SequenceNumberProviderInterface
     */
    protected function createSequenceNumberProvider()
    {
        return $this->getFactory()
            ->createSequenceNumberSequenceNumberProvider(
                $this->createQueryContainer()
            );
    }

    /**
     * @return Key\KeyHashProvider
     */
    protected function createKeyHashProvider()
    {
        return $this->getFactory()->createKeyKeyHashProvider();
    }

    /**
     * @return Mode\ModeDetectorInterface
     */
    protected function createModeDetector()
    {
        return $this->getFactory()->createModeModeDetector();
    }

    /**
     * @param array $requestParams
     * @return TransactionStatusRequest
     */
    protected function createTransactionStatusUpdateRequest(array $requestParams)
    {
        return new TransactionStatusRequest($requestParams);
    }

    /**
     * @todo move implementation in PayoneConfig
     * @return array
     */
    protected function getAvailablePaymentMethods()
    {
        return [
            PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT => $this->getFactory()->createPaymentMethodMapperPrePayment(),
            PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO => $this->getFactory()->createPaymentMethodMapperCreditCardPseudo(),
            PayoneApiConstants::PAYMENT_METHOD_PAYPAL => $this->getFactory()->createPaymentMethodMapperPayPal()
        ];
    }

    /**
     * @return StandardParameterInterface
     */
    protected function createStandardParameter()
    {
        if ($this->standardParameter === null) {
            $this->standardParameter = $this->getConfig()->getRequestStandardParameter();
        }

        return $this->standardParameter;
    }

}
