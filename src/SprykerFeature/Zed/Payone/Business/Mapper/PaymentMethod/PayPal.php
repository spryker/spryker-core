<?php

namespace SprykerFeature\Zed\Payone\Business\Mapper\PaymentMethod;


use SprykerFeature\Shared\Payone\Transfer\AuthorizationDataInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\CreditCardContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\EWalletContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;

class PayPal extends AbstractMapper
{

    /**
     * @return string
     */
    public function getName()
    {
        return self::PAYMENT_METHOD_PAYPAL;
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return CreditCardContainer
     */
    public function mapAuthorization(AuthorizationDataInterface $authorizationData)
    {
        $authorizationContainer = new AuthorizationContainer();

        $redirectContainer = new RedirectContainer();
        $redirectContainer->setSuccessUrl($this->getStandardParameter()->getRedirectSuccessUrl());
        $redirectContainer->setBackUrl($this->getStandardParameter()->getRedirectBackUrl());
        $redirectContainer->setErrorUrl($this->getStandardParameter()->getRedirectErrorUrl());

        $paymentMethodContainer = new EWalletContainer();
        $paymentMethodContainer->setWalletType(self::EWALLET_TYPE_PAYPAL);
        $paymentMethodContainer->setRedirect($redirectContainer);

        $authorizationContainer->setPaymentMethod($paymentMethodContainer);

        return $authorizationContainer;
    }

    /**
     * @param AuthorizationDataInterface $authorizationData
     * @return AbstractRequestContainer
     */
    public function mapPreAuthorization(AuthorizationDataInterface $authorizationData)
    {
        $authorizationContainer = new PreAuthorizationContainer();

        $redirectContainer = new RedirectContainer();
        $redirectContainer->setSuccessUrl($this->getStandardParameter()->getRedirectSuccessUrl());
        $redirectContainer->setBackUrl($this->getStandardParameter()->getRedirectBackUrl());
        $redirectContainer->setErrorUrl($this->getStandardParameter()->getRedirectErrorUrl());

        $paymentMethodContainer = new EWalletContainer();
        $paymentMethodContainer->setWalletType(self::EWALLET_TYPE_PAYPAL);
        $paymentMethodContainer->setRedirect($redirectContainer);

        $authorizationContainer->setPaymentMethod($paymentMethodContainer);

        return $authorizationContainer;
    }

}
