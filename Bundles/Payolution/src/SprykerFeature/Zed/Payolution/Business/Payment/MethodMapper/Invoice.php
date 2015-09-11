<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Account;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Payment;
use SprykerFeature\Zed\Payolution\Business\Api\Request\PreAuthorizationRequest;
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
     * @return PreAuthorizationRequest
     */
    public function mapToPreAuthorization(SpyPaymentPayolution $paymentEntity)
    {
        $orderEntity = $paymentEntity->getSpySalesOrder();
        $addressEntity = $orderEntity->getBillingAddress();
        $customerEntity = $orderEntity->getCustomer();

        $request = new PreAuthorizationRequest();
        $request->setSecuritySender($this->getConfig()->getSecuritySender())
            ->setUserLogin($this->getConfig()->getUserLogin())
            ->setUserPassword($this->getConfig()->getUserPassword())
            ->setPresentationAmount($orderEntity->getGrandTotal()/100)
            ->setPresentationCurrency(Store::getInstance()->getCurrencyIsoCode())
            ->setPresentationUsage($orderEntity->getIdSalesOrder())
            ->setPaymentCode(Payment::CODE_PRE_AUTHORIZATION)
            ->setAdressCountryIso2Code($addressEntity->getCountry()->getIso2Code())
            ->setAdressCity($addressEntity->getCity())
            ->setAdressZip($addressEntity->getZipCode())
            ->setAdressStreet($addressEntity->getAddress1())
            ->setNameFamily($customerEntity->getLastName())
            ->setNameGiven($customerEntity->getFirstName())
            ->setNameSex($this->mapGender($customerEntity->getGender()))
            ->setNameBirthdate($customerEntity->getDateOfBirth('Y-m-d'))
            ->setNameTitle($customerEntity->getSalutation())
            ->setContactIp($paymentEntity->getClientIp())
            ->setContactEmail($customerEntity->getEmail())
            ->setAccountBrand(Account::BRAND_INVOICE)
            ->setTransactionChannel($this->getConfig()->getChannelInvoice())
            ->setTransactionMode($this->getConfig()->getTransactionMode())
            ->setIdentificationTransactionId(uniqid('tran_'))
            ->setIdentificationShopperId($customerEntity->getIdCustomer());

        return $request;
    }

    private function mapGender($gender)
    {
        $genderMap = [
            SpyCustomerTableMap::COL_GENDER_MALE => "M",
            SpyCustomerTableMap::COL_GENDER_FEMALE => "F",
        ];

        return $genderMap[$gender];
    }

}
