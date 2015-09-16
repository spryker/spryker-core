<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PayolutionRequestAnalysisCriterionTransfer;
use Generated\Shared\Transfer\PayolutionRequestTransfer;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

abstract class AbstractMethodMapper implements MethodMapperInterface
{

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
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return PayolutionRequestTransfer
     */
    public function mapToPreAuthorization(SpyPaymentPayolution $paymentEntity)
    {
        $orderEntity = $paymentEntity->getSpySalesOrder();
        $addressEntity = $orderEntity->getBillingAddress();

        $requestTransfer = $this->getBaseRequestTransfer($paymentEntity);
        $requestTransfer
            ->setPaymentCode(Constants::PAYMENT_CODE_PRE_AUTHORIZATION)
            ->setAddressCountry($addressEntity->getCountry()->getIso2Code())
            ->setAddressCity($addressEntity->getCity())
            ->setAddressZip($addressEntity->getZipCode())
            ->setAddressStreet($addressEntity->getAddress1())
            ->setNameFamily($paymentEntity->getLastName())
            ->setNameGiven($paymentEntity->getFirstName())
            ->setNameSex($this->mapGender($paymentEntity->getGender()))
            ->setNameBirthdate($paymentEntity->getBirthdate())
            ->setNameTitle($paymentEntity->getSalutation())
            ->setContactIp($paymentEntity->getClientIp())
            ->setContactEmail($paymentEntity->getEmail())
            ->setIdentificationShopperid($orderEntity->getFkCustomer());

        $criteria = [
            Constants::CRITERION_CUSTOMER_LANGUAGE => Store::getInstance()->getCurrentLanguage(),
            Constants::CRITERION_DURATION => 12,
        ];
        foreach ($criteria as $name => $value) {
            $criterionTransfer = (new PayolutionRequestAnalysisCriterionTransfer())
                ->setName($name)
                ->setValue($value);
            $requestTransfer->addAnalysis($criterionTransfer);
        }

        return $requestTransfer;
    }

    /**
     * @param $gender
     *
     * @return string
     */
    private function mapGender($gender)
    {
        $genderMap = [
            SpyCustomerTableMap::COL_GENDER_MALE => 'M',
            SpyCustomerTableMap::COL_GENDER_FEMALE => 'F',
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
        $requestTransfer = $this->getBaseRequestTransfer($paymentEntity);
        $requestTransfer
            ->setPaymentCode(Constants::PAYMENT_CODE_RE_AUTHORIZACTION)
            ->setIdentificationReferenceid($uniqueId);

        return $requestTransfer;
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return PayolutionRequestTransfer
     */
    protected function getBaseRequestTransfer(SpyPaymentPayolution $paymentEntity)
    {
        $orderEntity = $paymentEntity->getSpySalesOrder();

        return (new PayolutionRequestTransfer())
            ->setSecuritySender($this->getConfig()->getSecuritySender())
            ->setUserLogin($this->getConfig()->getUserLogin())
            ->setUserPwd($this->getConfig()->getUserPassword())
            ->setPresentationAmount($orderEntity->getGrandTotal()/100)
            ->setPresentationCurrency(Store::getInstance()->getCurrencyIsoCode())
            ->setPresentationUsage($orderEntity->getIdSalesOrder())
            ->setAccountBrand($this->getAccountBrand())
            ->setTransactionChannel($this->getConfig()->getChannelInvoice())
            ->setTransactionMode($this->getConfig()->getTransactionMode())
            ->setIdentificationTransactionid(uniqid('tran_'));
    }

    /**
     * @return string
     */
    abstract public function getAccountBrand();

}
