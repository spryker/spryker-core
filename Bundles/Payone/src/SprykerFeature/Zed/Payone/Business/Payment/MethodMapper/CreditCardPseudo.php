<?php

namespace SprykerFeature\Zed\Payone\Business\Payment\MethodMapper;

use SprykerFeature\Shared\Payone\Dependency\Transfer\AuthorizationDataInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\CreditCardContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;

class CreditCardPseudo extends AbstractMapper
{

    /**
     * @return string
     */
    public function getName()
    {
        return self::PAYMENT_METHOD_CREDITCARD_PSEUDO;
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return CreditCardContainer
     */
    public function mapAuthorization(AuthorizationDataInterface $authorizationData)
    {
        $authorizationContainer = new AuthorizationContainer();

        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setReference($authorizationData->getReferenceId());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $authorizationContainer->setClearingType(self::CLEARING_TYPE_CREDITCARD);
        $authorizationContainer->setAmount($authorizationData->getAmount());

        $authorizationContainer->setPersonalData($this->createAuthorizationPersonalData($authorizationData));
        $authorizationContainer->setPaymentMethod($this->createPaymentMethodContainer($authorizationData));

        return $authorizationContainer;
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return AbstractRequestContainer
     */
    public function mapPreAuthorization(AuthorizationDataInterface $authorizationData)
    {
        $authorizationContainer = new PreAuthorizationContainer();

        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setReference($authorizationData->getReferenceId());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $authorizationContainer->setClearingType(self::CLEARING_TYPE_CREDITCARD);
        $authorizationContainer->setAmount($authorizationData->getAmount());

        $authorizationContainer->setPersonalData($this->createAuthorizationPersonalData($authorizationData));
        $authorizationContainer->setPaymentMethod($this->createPaymentMethodContainer($authorizationData));

        return $authorizationContainer;
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return CreditCardContainer
     */
    protected function createPaymentMethodContainer(AuthorizationDataInterface $authorizationData)
    {
        $paymentMethodContainer = new CreditCardContainer();
        $paymentMethodContainer->setPseudoCardPan($authorizationData->getPaymentUserData()->getCreditCardPseudoCardPan());
        $paymentMethodContainer->setRedirect($this->createRedirectContainer());

        return $paymentMethodContainer;
    }

    /**
     * @return RedirectContainer
     */
    protected function createRedirectContainer()
    {
        $redirectContainer = new RedirectContainer();

        $redirectContainer->setSuccessUrl($this->getStandardParameter()->getRedirectSuccessUrl());
        $redirectContainer->setBackUrl($this->getStandardParameter()->getRedirectBackUrl());
        $redirectContainer->setErrorUrl($this->getStandardParameter()->getRedirectErrorUrl());

        return $redirectContainer;
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return PersonalContainer
     */
    protected function createAuthorizationPersonalData(AuthorizationDataInterface $authorizationData)
    {
        $personalContainer = new PersonalContainer();

        // @todo fix country and order transfer interface (sales refactoring?)
        $personalContainer->setFirstName($authorizationData->getOrder()->getFirstName());
        $personalContainer->setLastName($authorizationData->getOrder()->getLastName());
        $personalContainer->setCountry($this->getStandardParameter()->getLanguage());

        return $personalContainer;
    }

}
