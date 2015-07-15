<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Payone\AuthorizationInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\EWalletContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerFeature\Shared\Payone\PayoneApiConstants;

class PayPal extends AbstractMapper
{

    /**
     * @return string
     */
    public function getName()
    {
        return PayoneApiConstants::PAYMENT_METHOD_PAYPAL;
    }

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return AbstractRequestContainer
     */
    public function mapAuthorization(AuthorizationInterface $authorizationData)
    {
        $authorizationContainer = new AuthorizationContainer();

        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setReference($authorizationData->getReferenceId());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_EWALLET);
        $authorizationContainer->setAmount($authorizationData->getAmount());

        $authorizationContainer->setPersonalData($this->createAuthorizationPersonalData($authorizationData));
        $authorizationContainer->setPaymentMethod($this->createPaymentMethodContainer($authorizationData));

        return $authorizationContainer;
    }

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return AbstractRequestContainer
     */
    public function mapPreAuthorization(AuthorizationInterface $authorizationData)
    {
        $authorizationContainer = new PreAuthorizationContainer();

        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setReference($authorizationData->getReferenceId());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_EWALLET);
        $authorizationContainer->setAmount($authorizationData->getAmount());

        $authorizationContainer->setPersonalData($this->createAuthorizationPersonalData($authorizationData));
        $authorizationContainer->setPaymentMethod($this->createPaymentMethodContainer($authorizationData));

        return $authorizationContainer;
    }

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return EWalletContainer
     */
    protected function createPaymentMethodContainer(AuthorizationInterface $authorizationData)
    {
        $paymentMethodContainer = new EWalletContainer();

        $paymentMethodContainer->setWalletType(PayoneApiConstants::EWALLET_TYPE_PAYPAL);
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
     * @param AuthorizationInterface $authorizationData
     *
     * @return PersonalContainer
     */
    protected function createAuthorizationPersonalData(AuthorizationInterface $authorizationData)
    {
        $personalContainer = new PersonalContainer();

        $personalContainer->setFirstName($authorizationData->getOrder()->getFirstName());
        $personalContainer->setLastName($authorizationData->getOrder()->getLastName());
        $personalContainer->setCountry($this->storeConfig->getCurrentCountry());

        return $personalContainer;
    }

}
