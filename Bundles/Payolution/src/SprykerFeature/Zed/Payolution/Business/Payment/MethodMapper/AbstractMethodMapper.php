<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\PayolutionRequestAnalysisCriterionTransfer;
use Generated\Shared\Transfer\PayolutionRequestTransfer;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Payolution\Persistence\Propel\Map\SpyPaymentPayolutionTableMap;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

abstract class AbstractMethodMapper implements MethodMapperInterface
{

    const PAYOLUTION_DATE_FORMAT = 'Y-m-d';

    /**
     * @var PayolutionConfig
     */
    private $config;

    /**
     * @param PayolutionConfig $config
     */
    public function __construct(PayolutionConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return PayolutionConfig
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @param CheckoutRequestTransfer $checkoutRequestTransfer
     *
     * @return PayolutionRequestTransfer
     */
    public function mapToPreCheck(CheckoutRequestTransfer $checkoutRequestTransfer)
    {
        $payolutionTransfer = $checkoutRequestTransfer->getPayolutionPayment();
        $cart = $checkoutRequestTransfer->getCart();
        $grandTotal = $cart->getTotals()->getGrandTotal();
        $requestTransfer = $this->getBaseRequestTransfer(
            $grandTotal,
            $payolutionTransfer->getCurrencyIso3Code(),
            $isSalesOrder = null
        );
        $requestTransfer->setPaymentCode(Constants::PAYMENT_CODE_PRE_CHECK);

        // Pre-check requires to set a specific transaction channel
        $requestTransfer->setTransactionChannel($this->config->getTransactionChannelPreCheck());

        $addressTransfer = $payolutionTransfer->getAddress();
        $requestTransfer
            ->setNameGiven($addressTransfer->getFirstName())
            ->setNameFamily($addressTransfer->getLastName())
            ->setNameTitle($addressTransfer->getSalutation())
            ->setNameSex($this->mapGender($payolutionTransfer->getGender()))
            ->setNameBirthdate($payolutionTransfer->getDateOfBirth())
            ->setAddressZip($addressTransfer->getZipCode())
            ->setAddressCity($addressTransfer->getCity())
            ->setAddressCountry($addressTransfer->getIso2Code())
            ->setContactEmail($addressTransfer->getEmail())
            ->setContactPhone($addressTransfer->getPhone())
            ->setContactMobile($addressTransfer->getCellPhone())
            ->setContactIp($payolutionTransfer->getClientIp());

        // Payolution requires a single street address string
        $formattedStreet = trim(sprintf(
            '%s %s %s',
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getAddress3()
        ));
        $requestTransfer->setAddressStreet($formattedStreet);

        $criteria = [
            Constants::CRITERION_PRE_CHECK => 'TRUE',
            Constants::CRITERION_CUSTOMER_LANGUAGE => $payolutionTransfer->getLanguageIso2Code(),
        ];
        $this->addAnalysisCriteriaToRequestTransfer($criteria, $requestTransfer);

        return $requestTransfer;
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return PayolutionRequestTransfer
     */
    public function mapToPreAuthorization(SpyPaymentPayolution $paymentEntity)
    {
        $orderEntity = $paymentEntity->getSpySalesOrder();

        $requestTransfer = $this->getBaseRequestTransferForPayment($paymentEntity);
        $requestTransfer
            ->setPaymentCode(Constants::PAYMENT_CODE_PRE_AUTHORIZATION)
            ->setAddressCountry($paymentEntity->getCountryIso2Code())
            ->setAddressCity($paymentEntity->getCity())
            ->setAddressZip($paymentEntity->getZipCode())
            ->setAddressStreet($paymentEntity->getStreet())
            ->setNameFamily($paymentEntity->getLastName())
            ->setNameGiven($paymentEntity->getFirstName())
            ->setNameSex($this->mapGender($paymentEntity->getGender()))
            ->setNameBirthdate($paymentEntity->getDateOfBirth(self::PAYOLUTION_DATE_FORMAT))
            ->setNameTitle($paymentEntity->getSalutation())
            ->setContactIp($paymentEntity->getClientIp())
            ->setContactEmail($paymentEntity->getEmail())
            ->setContactPhone($paymentEntity->getPhone())
            ->setContactMobile($paymentEntity->getCellPhone())
            ->setIdentificationShopperid($orderEntity->getFkCustomer());

        $criteria = [
            Constants::CRITERION_CUSTOMER_LANGUAGE => $paymentEntity->getLanguageIso2Code(),
        ];
        $this->addAnalysisCriteriaToRequestTransfer($criteria, $requestTransfer);

        return $requestTransfer;
    }

    /**
     * @param string $gender
     *
     * @return string
     */
    private function mapGender($gender)
    {
        $genderMap = [
            SpyPaymentPayolutionTableMap::COL_GENDER_MALE => Constants::SEX_MALE,
            SpyPaymentPayolutionTableMap::COL_GENDER_FEMALE => Constants::SEX_FEMALE,
        ];

        return $genderMap[$gender];
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return PayolutionRequestTransfer
     */
    public function mapToReAuthorization(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
        $requestTransfer = $this->getBaseRequestTransferForPayment($paymentEntity);
        $requestTransfer
            ->setPaymentCode(Constants::PAYMENT_CODE_RE_AUTHORIZATION)
            ->setIdentificationReferenceid($uniqueId);

        return $requestTransfer;
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param int $uniqueId
     *
     * @return PayolutionRequestTransfer
     */
    public function mapToReversal(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
        $requestTransfer = $this->getBaseRequestTransferForPayment($paymentEntity);
        $requestTransfer
            ->setPaymentCode(Constants::PAYMENT_CODE_REVERSAL)
            ->setIdentificationReferenceid($uniqueId);

        return $requestTransfer;
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param int $uniqueId
     *
     * @return PayolutionRequestTransfer
     */
    public function mapToRefund(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
        $requestTransfer = $this->getBaseRequestTransferForPayment($paymentEntity);
        $requestTransfer
            ->setPaymentCode(Constants::PAYMENT_CODE_REFUND)
            ->setIdentificationReferenceid($uniqueId);

        return $requestTransfer;
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return PayolutionRequestTransfer
     */
    protected function getBaseRequestTransferForPayment(SpyPaymentPayolution $paymentEntity)
    {
        $orderEntity = $paymentEntity->getSpySalesOrder();
        $requestTransfer = $this->getBaseRequestTransfer(
            $orderEntity->getGrandTotal(),
            $paymentEntity->getCurrencyIso3Code(),
            $orderEntity->getIdSalesOrder()
        );
        $requestTransfer->setPresentationCurrency($paymentEntity->getCurrencyIso3Code());

        return $requestTransfer;
    }

    /**
     * @param int $grandTotal
     * @param string $currency
     * @param int $idOrder
     *
     * @return PayolutionRequestTransfer
     */
    protected function getBaseRequestTransfer($grandTotal, $currency, $idOrder)
    {
        $requestTransfer = new PayolutionRequestTransfer();
        $requestTransfer
            ->setSecuritySender($this->getConfig()->getSecuritySender())
            ->setUserLogin($this->getConfig()->getUserLogin())
            ->setUserPwd($this->getConfig()->getUserPassword())
            ->setPresentationAmount($grandTotal / 100)
            ->setPresentationUsage($idOrder)
            ->setPresentationCurrency($currency)
            ->setAccountBrand($this->getAccountBrand())
            ->setTransactionChannel($this->getConfig()->getTransactionChannelSync())
            ->setTransactionMode($this->getConfig()->getTransactionMode())
            ->setIdentificationTransactionid(uniqid('tran_'));

        return $requestTransfer;
    }

    /**
     * @param string[] $criteria
     * @param PayolutionRequestTransfer $requestTransfer
     *
     * @return PayolutionRequestTransfer
     */
    protected function addAnalysisCriteriaToRequestTransfer(array $criteria, PayolutionRequestTransfer $requestTransfer)
    {
        foreach ($criteria as $name => $value) {
            $criterionTransfer = new PayolutionRequestAnalysisCriterionTransfer();
            $criterionTransfer
                ->setName($name)
                ->setValue($value);
            $requestTransfer->addAnalysisCriterion($criterionTransfer);
        }

        return $requestTransfer;
    }

}
