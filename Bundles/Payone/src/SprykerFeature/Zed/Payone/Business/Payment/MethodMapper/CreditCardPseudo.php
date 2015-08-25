<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Payone\PayoneAuthorizationInterface;
use Generated\Shared\Payone\PayoneCreditCardInterface;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\CreditCardPseudoContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\CreditCardCheckContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;

class CreditCardPseudo extends AbstractMapper
{

    /**
     * @return string
     */
    public function getName()
    {
        return PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity)
    {
        $authorizationContainer = new AuthorizationContainer();
        $authorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $authorizationContainer);

        return $authorizationContainer;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return CaptureContainer
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentEntity)
    {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $captureContainer = new CaptureContainer();
        $captureContainer->setAmount($paymentDetailEntity->getAmount());
        $captureContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $captureContainer->setTxid($paymentEntity->getTransactionId());

        return $captureContainer;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return PreAuthorizationContainer
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity)
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();
        $preAuthorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $preAuthorizationContainer);

        return $preAuthorizationContainer;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     * @param AbstractAuthorizationContainer $authorizationContainer
     *
     * @return AbstractAuthorizationContainer
     */
    protected function mapPaymentToAbstractAuthorization(SpyPaymentPayone $paymentEntity, AbstractAuthorizationContainer $authorizationContainer)
    {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_CREDIT_CARD);
        $authorizationContainer->setReference($paymentEntity->getReference());
        $authorizationContainer->setAmount($paymentDetailEntity->getAmount());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $authorizationContainer->setPaymentMethod($this->createPaymentMethodContainerFromPayment($paymentEntity));

        $billingAddressEntity = $paymentEntity->getSpySalesOrder()->getBillingAddress();

        $personalContainer = new PersonalContainer();
        $this->mapBillingAddressToPersonalContainer($personalContainer, $billingAddressEntity);

        $authorizationContainer->setPersonalData($personalContainer);

        return $authorizationContainer;
    }

    /**
     * @param PayoneCreditCardInterface $payoneCreditCardTransfer
     *
     * @return CreditCardCheckContainer
     */
    public function mapCreditCardCheck(PayoneCreditCardInterface $payoneCreditCardTransfer)
    {
        $creditCardCheckContainer = new CreditCardCheckContainer();

        $creditCardCheckContainer->setAid($this->getStandardParameter()->getAid());
        $creditCardCheckContainer->setCardPan($payoneCreditCardTransfer->getCardPan());
        $creditCardCheckContainer->setCardType($payoneCreditCardTransfer->getCardType());
        $creditCardCheckContainer->setCardExpireDate($payoneCreditCardTransfer->getCardExpireDate());
        $creditCardCheckContainer->setCardCvc2($payoneCreditCardTransfer->getCardCvc2());
        $creditCardCheckContainer->setCardIssueNumber($payoneCreditCardTransfer->getCardIssueNumber());
        $creditCardCheckContainer->setStoreCardData($payoneCreditCardTransfer->getStoreCardData());
        $creditCardCheckContainer->setLanguage($this->getStandardParameter()->getLanguage());

        return $creditCardCheckContainer;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return DebitContainer
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentEntity)
    {
        $debitContainer = new DebitContainer();

        $debitContainer->setTxid($paymentEntity->getTransactionId());
        $debitContainer->setSequenceNumber($this->getNextSequenceNumber($paymentEntity->getTransactionId()));
        $debitContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $debitContainer->setAmount($paymentEntity->getSpyPaymentPayoneDetail()->getAmount());

        return $debitContainer;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return RefundContainer
     */
    public function mapPaymentToRefund(SpyPaymentPayone $paymentEntity)
    {
        $refundContainer = new RefundContainer();

        $refundContainer->setTxid($paymentEntity->getTransactionId());
        $refundContainer->setSequenceNumber($this->getNextSequenceNumber($paymentEntity->getTransactionId()));
        $refundContainer->setCurrency($this->getStandardParameter()->getCurrency());

        return $refundContainer;
    }

    /**
     * @param SpyPaymentPayone $paymentEntity
     *
     * @return CreditCardPseudoContainer
     */
    protected function createPaymentMethodContainerFromPayment(SpyPaymentPayone $paymentEntity)
    {
        $paymentMethodContainer = new CreditCardPseudoContainer();

        $pseudoCardPan = $paymentEntity->getSpyPaymentPayoneDetail()->getPseudocardpan();
        $paymentMethodContainer->setPseudoCardPan($pseudoCardPan);

        return $paymentMethodContainer;
    }

    /**
     * @param PayoneAuthorizationInterface $payoneAuthorizationTransfer
     *
     * @return PersonalContainer
     */
    protected function createAuthorizationPersonalData(PayoneAuthorizationInterface $payoneAuthorizationTransfer)
    {
        $personalContainer = new PersonalContainer();

        $personalContainer->setFirstName($payoneAuthorizationTransfer->getOrder()->getFirstName());
        $personalContainer->setLastName($payoneAuthorizationTransfer->getOrder()->getLastName());
        $personalContainer->setCountry($this->storeConfig->getCurrentCountry());

        return $personalContainer;
    }

}
