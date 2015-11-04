<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Method;

use Generated\Shared\Transfer\AddressTransfer;
use SprykerFeature\Zed\Payolution\Business\Exception\GenderNotDefinedException;
use SprykerFeature\Zed\Payolution\Persistence\Propel\Map\SpyPaymentPayolutionTableMap;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

abstract class AbstractPaymentMethod
{

    const PAYOLUTION_DATE_FORMAT = 'Y-m-d';

    /**
     * @const array
     */
    protected static $genderMap = array(
        SpyPaymentPayolutionTableMap::COL_GENDER_MALE => ApiConstants::SEX_MALE,
        SpyPaymentPayolutionTableMap::COL_GENDER_FEMALE => ApiConstants::SEX_FEMALE,
    );

    /**
     * @var PayolutionConfig
     */
    protected $config;

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
     * @return string
     */
    abstract public function getAccountBrand();

    /**
     * @return string
     */
    abstract public function getTransactionChannel();

    /**
     * @param int $grandTotal
     * @param string $currency
     * @param string $idOrder
     *
     * @return array
     */
    protected function getBaseRequestTransfer($grandTotal, $currency, $idOrder)
    {
        return [
            ApiConstants::ACCOUNT_BRAND => $this->getAccountBrand(),
            ApiConstants::TRANSACTION_MODE => $this->getConfig()->getTransactionMode(),
            ApiConstants::SECURITY_SENDER => $this->getConfig()->getTransactionSecuritySender(),
            ApiConstants::USER_LOGIN => $this->getConfig()->getTransactionUserLogin(),
            ApiConstants::USER_PWD => $this->getConfig()->getTransactionUserPassword(),
            ApiConstants::PRESENTATION_AMOUNT => $grandTotal / 100,
            ApiConstants::PRESENTATION_USAGE => $idOrder,
            ApiConstants::PRESENTATION_CURRENCY => $currency,
            ApiConstants::IDENTIFICATION_TRANSACTIONID => uniqid('tran_'),
        ];
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $paymentCode
     * @param string $uniqueId
     *
     * @return array
     */
    protected function getBaseRequestTransferForPayment(
        SpyPaymentPayolution $paymentEntity,
        $paymentCode,
        $uniqueId
    ) {
        $orderEntity = $paymentEntity->getSpySalesOrder();

        $requestData = $this->getBaseRequestTransfer(
            $orderEntity->getGrandTotal(),
            $paymentEntity->getCurrencyIso3Code(),
            $orderEntity->getIdSalesOrder()
        );

        $this->addRequestData(
            $requestData,
            [
                ApiConstants::TRANSACTION_CHANNEL => $this->getTransactionChannel(),
                ApiConstants::PAYMENT_CODE => $paymentCode,
                ApiConstants::IDENTIFICATION_REFERENCEID => $uniqueId,
                ApiConstants::NAME_GIVEN => $paymentEntity->getFirstName(),
                ApiConstants::NAME_FAMILY => $paymentEntity->getLastName(),
                ApiConstants::NAME_TITLE => $paymentEntity->getSalutation(),
                ApiConstants::NAME_SEX => $this->mapGender($paymentEntity->getGender()),
                ApiConstants::NAME_BIRTHDATE => $paymentEntity->getDateOfBirth(self::PAYOLUTION_DATE_FORMAT),
                ApiConstants::ADDRESS_STREET => $paymentEntity->getStreet(),
                ApiConstants::ADDRESS_ZIP => $paymentEntity->getZipCode(),
                ApiConstants::ADDRESS_CITY => $paymentEntity->getCity(),
                ApiConstants::ADDRESS_COUNTRY => $paymentEntity->getCountryIso2Code(),
                ApiConstants::CONTACT_EMAIL => $paymentEntity->getEmail(),
                ApiConstants::CONTACT_PHONE => $paymentEntity->getPhone(),
                ApiConstants::CONTACT_MOBILE => $paymentEntity->getCellPhone(),
                ApiConstants::CONTACT_IP => $paymentEntity->getClientIp(),
            ]
        );

        return $requestData;
    }

    /**
     * @param array $requestData
     * @param array $additionalData
     *
     * @return void
     */
    protected function addRequestData(&$requestData, $additionalData)
    {
        foreach ($additionalData as $field_name => $value) {
            $requestData[$field_name] = $value;
        }
    }

    /**
     * @param string $gender
     *
     * @throws GenderNotDefinedException
     *
     * @return string
     */
    protected function mapGender($gender)
    {
        if (!isset(self::$genderMap[$gender])) {
            throw new GenderNotDefinedException('The given gender is not defined.');
        }

        return self::$genderMap[$gender];
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function formatAddress($addressTransfer)
    {
        return trim(sprintf(
            '%s %s %s',
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getAddress3()
        ));
    }

}
