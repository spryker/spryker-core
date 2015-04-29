<?php

namespace SprykerFeature\Zed\Payone\Business;

use SprykerFeature\Shared\Payone\Transfer\StandardParameterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payone\Business\Api\ApiConstants;
use SprykerEngine\Zed\Kernel\Business\Factory;
use Generated\Zed\Ide\FactoryAutoCompletion\PayoneBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Payone\Business\Mapper\PaymentMethodMapperInterface;
use SprykerFeature\Zed\Payone\PayoneConfig;

/**
 * @method Factory|PayoneBusiness getFactory()
 * @method PayoneConfig getConfig()
 */
class PayoneDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @todo Is it allowed to cache here?
     * @var StandardParameterInterface
     */
    private $standardParameter;


    /**
     * @param string $paymentMethodName
     * @return PaymentManager
     */
    public function createPaymentManager($paymentMethodName)
    {
        return $this->getFactory()
            ->createPaymentManager(
                $this->getLocator(),
                $this->createExecutionAdapter(),
                $this->createPaymentMethodRegistry()->findPaymentMethodMapperByName($paymentMethodName),
                $this->createStandardParameter(),
                $this->createSequenceNumberProvider(),
                $this->createModeDetector()
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
     * @param string $name
     * @return PaymentMethodMapperInterface
     */
    protected function getPaymentMethodMapperByName($name)
    {
        return $this->createPaymentMethodRegistry()->findPaymentMethodMapperByName($name);
    }

    /**
     * @return PaymentMethodRegistryInterface
     */
    protected function createPaymentMethodRegistry()
    {
        $registry = $this->getFactory()->createPaymentMethodRegistry();

        foreach ($this->getAvailablePaymentMethods() as $paymentMethod) {
            $registry->registerPaymentMethodMapper($paymentMethod);
        }

        return $registry;
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
     * @return ModeDetectorInterface
     */
    protected function createModeDetector()
    {
        return $this->getFactory()->createModeDetector();
    }

    /**
     * @todo move implementation in PayoneConfig
     * @return array
     */
    protected function getAvailablePaymentMethods()
    {
        return [
            ApiConstants::PAYMENT_METHOD_PREPAYMENT => $this->getFactory()->createPaymentMethodMapperPrePayment(),
            ApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO => $this->getFactory()->createPaymentMethodMapperCreditCardPseudo()
        ];
    }

    /**
     * @todo is it allowed to cache like this???
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
