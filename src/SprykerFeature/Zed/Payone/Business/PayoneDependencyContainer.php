<?php

namespace SprykerFeature\Zed\Payone\Business;

use SprykerFeature\Shared\Payone\PayoneConfig;
use SprykerFeature\Shared\Payone\Transfer\StandardParameterInterface;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payone\Business\Api\ApiConstants;
use SprykerEngine\Zed\Kernel\Business\Factory;
use Generated\Zed\Ide\FactoryAutoCompletion\PayoneBusiness;
use \SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;

/**
 * @method Factory|PayoneBusiness getFactory()
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
     * @return PayoneSettings
     */
    protected function createSettings()
    {
        return $this->getFactory()->createPayoneSettings();
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
        return $this->getFactory()
                ->createModeDetector();
    }

    /**
     * @todo implemetation in PayoneSettings???
     * @return array
     */
    protected function getAvailablePaymentMethods()
    {
        return [
            ApiConstants::PAYMENT_METHOD_PREPAYMENT
                => $this->getFactory()->createPaymentMethodMapperPrePayment(),
            ApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO
                => $this->getFactory()->createPaymentMethodMapperCreditCardPseudo()
        ];
    }

    /**
     * @todo implemetation in PayoneSettings???
     * @todo is it allowed to cache like this???
     * @return StandardParameterInterface
     */
    protected function createStandardParameter()
    {
        if ($this->standardParameter === null) {
            $settings = $config = $this->createSettings();
            $config = $settings->getCredentials();

            $this->standardParameter = $this->getLocator()->payone()->transferStandardParameter();

            $this->standardParameter->setEncoding($config[PayoneConfig::PAYONE_CREDENTIALS_ENCODING]);
            $this->standardParameter->setMid($config[PayoneConfig::PAYONE_CREDENTIALS_MID]);
            $this->standardParameter->setAid($config[PayoneConfig::PAYONE_CREDENTIALS_AID]);
            $this->standardParameter->setPortalId($config[PayoneConfig::PAYONE_CREDENTIALS_PORTAL_ID]);
            $this->standardParameter->setKey($config[PayoneConfig::PAYONE_CREDENTIALS_KEY]);

            $this->standardParameter->setPaymentGatewayUrl($config[PayoneConfig::PAYONE_PAYMENT_GATEWAY_URL]);
            $this->standardParameter->setCurrency(Store::getInstance()->getCurrencyIsoCode());
            $this->standardParameter->setLanguage(Store::getInstance()->getCurrentLanguage());

            $this->standardParameter->setRedirectSuccessUrl($settings->getRedirectSuccessUrl());
            $this->standardParameter->setRedirectBackUrl($settings->getRedirectBackUrl());
            $this->standardParameter->setRedirectErrorUrl($settings->getRedirectErrorUrl());
        }

        return $this->standardParameter;
    }

}
