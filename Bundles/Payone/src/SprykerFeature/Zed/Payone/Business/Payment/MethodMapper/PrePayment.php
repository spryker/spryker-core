<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Payone\AuthorizationInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerFeature\Shared\Payone\PayoneApiConstants;

class PrePayment extends AbstractMapper
{

    /**
     * @return string
     */
    public function getName()
    {
        return PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT;
    }

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return AuthorizationContainer
     */
    public function mapAuthorization(AuthorizationInterface $authorizationData)
    {
        $authorizationContainer = new AuthorizationContainer();

        $authorizationContainer->setPersonalData($this->createAuthorizationPersonalData($authorizationData));

        $authorizationContainer->setAmount($authorizationData->getAmount());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_PREPAYMENT);
        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setReference($authorizationData->getReferenceId());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());

        return $authorizationContainer;
    }

    /**
     * @param AuthorizationInterface $authorizationData
     *
     * @return AuthorizationContainer
     */
    public function mapPreAuthorization(AuthorizationInterface $authorizationData)
    {
        $authorizationContainer = new PreAuthorizationContainer();

        $authorizationContainer->setPersonalData($this->createAuthorizationPersonalData($authorizationData));

        $authorizationContainer->setAmount($authorizationData->getAmount());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_PREPAYMENT);
        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setReference($authorizationData->getReferenceId());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());

        return $authorizationContainer;
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
