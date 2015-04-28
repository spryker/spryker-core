<?php

namespace SprykerFeature\Zed\Payone\Business\PaymentMethodMapper;


use SprykerFeature\Shared\Payone\Transfer\AuthorizationDataInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\CreditCardContainer;
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

        $paymentMethodContainer = new CreditCardContainer();
        // @todo get pseudo card pan... !
        //$paymentMethodContainer->setPseudoCardPan();
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

        $paymentMethodContainer = new CreditCardContainer();
        // @todo get pseudo card pan... !
        //$paymentMethodContainer->setPseudoCardPan();
        $authorizationContainer->setPaymentMethod($paymentMethodContainer);

        return $authorizationContainer;
    }

}
