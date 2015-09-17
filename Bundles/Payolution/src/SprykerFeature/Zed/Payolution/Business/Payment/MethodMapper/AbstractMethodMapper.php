<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use Generated\Shared\Payolution\OrderInterface;
use Generated\Shared\Transfer\OrderTransfer;
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
     * @param OrderInterface $orderTransfer
     *
     * @return PayolutionRequestTransfer
     */
    public function mapToPreCheck(OrderTransfer $orderTransfer)
    {
        $requestTransfer = $this->getBaseRequestTransfer(
            $orderTransfer->getTotals()->getGrandTotal(),
            $orderTransfer->getIdSalesOrder()
        );
        $requestTransfer->setPaymentCode(Constants::PAYMENT_CODE_PRE_CHECK);

        $address = $orderTransfer->getBillingAddress();
        $requestTransfer
            ->setAddressZip($address->getZipCode())
            ->setAddressCity($address->getCity())
            //->setAddressCountry()
            ->setAddressStreet($address->getAddress1());

        $paymentTransfer = $orderTransfer->getPayolutionPayment();
        $requestTransfer
            ->setNameGiven($paymentTransfer->getFirstName())
            ->setNameFamily($paymentTransfer->getLastName())
            ->setNameTitle($paymentTransfer->getSalutation())
            ->setNameSex($this->mapGender($paymentTransfer->getGender()))
            ->setNameBirthdate($paymentTransfer->getBirthdate())
            ->setContactEmail($paymentTransfer->getEmail())
            ->setContactIp($paymentTransfer->getClientIp());

        $criterionTransfer = (new PayolutionRequestAnalysisCriterionTransfer())
            ->setName(Constants::CRITERION_PRE_CHECK)
            ->setValue('TRUE');
        $requestTransfer->addAnalysisCriterion($criterionTransfer);

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
        $addressEntity = $orderEntity->getBillingAddress();

        $requestTransfer = $this->getBaseRequestTransferForPayment($paymentEntity);
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
            $requestTransfer->addAnalysisCriterion($criterionTransfer);
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
        $requestTransfer = $this->getBaseRequestTransferForPayment($paymentEntity);
        $requestTransfer
            ->setPaymentCode(Constants::PAYMENT_CODE_RE_AUTHORIZATION)
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
        return $this->getBaseRequestTransfer($orderEntity->getGrandTotal(), $orderEntity->getIdSalesOrder());
    }

    /**
     * @param int $grandTotal
     * @param int $idOrder
     *
     * @return PayolutionRequestTransfer
     */
    protected function getBaseRequestTransfer($grandTotal, $idOrder)
    {
        return (new PayolutionRequestTransfer())
            ->setSecuritySender($this->getConfig()->getSecuritySender())
            ->setUserLogin($this->getConfig()->getUserLogin())
            ->setUserPwd($this->getConfig()->getUserPassword())
            ->setPresentationAmount($grandTotal / 100)
            ->setPresentationCurrency(Store::getInstance()->getCurrencyIsoCode())
            ->setPresentationUsage($idOrder)
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
