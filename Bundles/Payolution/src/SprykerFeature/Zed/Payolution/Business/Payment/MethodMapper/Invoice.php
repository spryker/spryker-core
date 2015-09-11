<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PayolutionRequestTransfer;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapperInterface;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

class Invoice extends AbstractMethodMapper
{

    /**
     * @return string
     */
    public function getName()
    {
        return MethodMapperInterface::INVOICE;
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
        $customerEntity = $orderEntity->getCustomer();

        $requestTransfer = (new PayolutionRequestTransfer())
            ->setSecuritySender($this->getConfig()->getSecuritySender())
            ->setUserLogin($this->getConfig()->getUserLogin())
            ->setUserPwd($this->getConfig()->getUserPassword())
            ->setPresentationAmount($orderEntity->getGrandTotal()/100)
            ->setPresentationCurrency(Store::getInstance()->getCurrencyIsoCode())
            ->setPresentationUsage($orderEntity->getIdSalesOrder())
            ->setPaymentCode(Constants::PAYMENT_CODE_PRE_AUTHORIZATION)
            ->setAddressCountry($addressEntity->getCountry()->getIso2Code())
            ->setAddressCity($addressEntity->getCity())
            ->setAddressZip($addressEntity->getZipCode())
            ->setAddressStreet($addressEntity->getAddress1())
            ->setNameFamily($customerEntity->getLastName())
            ->setNameGiven($customerEntity->getFirstName())
            ->setNameSex($this->mapGender($customerEntity->getGender()))
            ->setNameBirthdate($customerEntity->getDateOfBirth('Y-m-d'))
            ->setNameTitle($customerEntity->getSalutation())
            ->setContactIp($paymentEntity->getClientIp())
            ->setContactEmail($customerEntity->getEmail())
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE)
            ->setTransactionChannel($this->getConfig()->getChannelInvoice())
            ->setTransactionMode($this->getConfig()->getTransactionMode())
            ->setIdentificationTransactionid(uniqid('tran_'))
            ->setIdentificationShopperid($customerEntity->getIdCustomer());

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

}
