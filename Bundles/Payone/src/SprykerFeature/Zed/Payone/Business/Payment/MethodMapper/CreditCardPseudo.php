<?php

namespace SprykerFeature\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Payone\AuthorizationInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
//use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod\CreditCardContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\CreditCardPseudoContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use Generated\Shared\Payone\CreditCardInterface as PayoneCreditCardInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerEngine\Shared\Kernel\Store;

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
     * @param AuthorizationInterface $authorizationData
     * @return CreditCardContainer
     */
    public function mapAuthorization(AuthorizationInterface $authorizationData)
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
     * @param AuthorizationInterface $authorizationData
     * @return AbstractRequestContainer
     */
    public function mapPreAuthorization(AuthorizationInterface $authorizationData)
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
     * @param PayoneCreditCardInterface $creditCardData
     * @return CreditCardCheckContainer
     */
    public function mapCreditCardCheck(PayoneCreditCardInterface $creditCardData)
    {
        $creditCardCheckContainer = new CreditCardCheckContainer();

        $creditCardCheckContainer->setAid($this->getStandardParameter()->getAid());
        $creditCardCheckContainer->setCardPan($creditCardData->getCardPan());
        $creditCardCheckContainer->setCardType($creditCardData->getCardType());
        $creditCardCheckContainer->setCardExpireDate($creditCardData->getCardExpireDate());
        $creditCardCheckContainer->setCardCvc2($creditCardData->getCardCvc2());
        $creditCardCheckContainer->setCardIssueNumber($creditCardData->getCardIssueNumber());
        $creditCardCheckContainer->setStoreCardData($creditCardData->getStoreCardData());
        $creditCardCheckContainer->setLanguage($this->getStandardParameter()->getLanguage());

        return $creditCardCheckContainer;
    }


    /**
     * @param AuthorizationInterface $authorizationData
     * @return CreditCardPseudoContainer
     */
    protected function createPaymentMethodContainer(AuthorizationInterface $authorizationData)
    {
        $paymentMethodContainer = new CreditCardPseudoContainer();

        $paymentMethodContainer->setPseudoCardPan($authorizationData->getPersonalData()->getPseudoCardPan());

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
     * @return PersonalContainer
     */
    protected function createAuthorizationPersonalData(AuthorizationInterface $authorizationData)
    {
        $personalContainer = new PersonalContainer();

        $personalContainer->setFirstName($authorizationData->getOrder()->getFirstName());
        $personalContainer->setLastName($authorizationData->getOrder()->getLastName());
        $personalContainer->setCountry(Store::getInstance()->getCurrentCountry());

        return $personalContainer;
    }

}
