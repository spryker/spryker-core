<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Braintree\Persistence\Map\SpyPaymentBraintreeTableMap;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintree;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Braintree\BraintreeConfig;
use Spryker\Zed\Braintree\Business\Exception\GenderNotDefinedException;

abstract class AbstractPaymentMethod
{

    const BRAINTREE_DATE_FORMAT = 'Y-m-d';

    /**
     * @var static string[]
     */
    protected static $genderMap = [
        SpyPaymentBraintreeTableMap::COL_GENDER_MALE => ApiConstants::SEX_MALE,
        SpyPaymentBraintreeTableMap::COL_GENDER_FEMALE => ApiConstants::SEX_FEMALE,
    ];

    /**
     * @var \Spryker\Zed\Braintree\BraintreeConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Braintree\BraintreeConfig $config
     */
    public function __construct(BraintreeConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Spryker\Zed\Braintree\BraintreeConfig
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
     * @param int $grandTotal
     * @param string $currency
     * @param string $idOrder
     *
     * @return array
     */
    protected function getBaseTransactionRequest($grandTotal, $currency, $idOrder)
    {
        return [
            ApiConstants::ACCOUNT_BRAND => $this->getAccountBrand(),
            ApiConstants::TRANSACTION_MODE => $this->getConfig()->getTransactionMode(),
            ApiConstants::USER_LOGIN => $this->getConfig()->getTransactionUserLogin(),
            ApiConstants::USER_PWD => $this->getConfig()->getTransactionUserPassword(),
            ApiConstants::PRESENTATION_AMOUNT => $this->getCurrencyManager()->convertCentToDecimal($grandTotal),
            ApiConstants::PRESENTATION_USAGE => $idOrder,
            ApiConstants::PRESENTATION_CURRENCY => $currency,
            ApiConstants::IDENTIFICATION_TRANSACTIONID => $idOrder,
            ApiConstants::CRITERION_REQUEST_SYSTEM_VENDOR => ApiConstants::CRITERION_REQUEST_SYSTEM_VENDOR_VALUE,
            ApiConstants::CRITERION_REQUEST_SYSTEM_VERSION => ApiConstants::CRITERION_REQUEST_SYSTEM_VERSION_VALUE,
            ApiConstants::CRITERION_REQUEST_SYSTEM_TYPE => ApiConstants::CRITERION_REQUEST_SYSTEM_TYPE_VALUE,
            ApiConstants::CRITERION_MODULE_NAME => ApiConstants::CRITERION_MODULE_NAME_VALUE,
            ApiConstants::CRITERION_MODULE_VERSION => ApiConstants::CRITERION_MODULE_VERSION_VALUE,
            ApiConstants::CRITERION_WEBSHOP_URL => $this->getConfig()->getWebshopUrl(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree $paymentEntity
     * @param string $paymentCode
     * @param string $uniqueId
     *
     * @return array
     */
    protected function getBaseTransactionRequestForPayment(
        OrderTransfer $orderTransfer,
        SpyPaymentBraintree $paymentEntity,
        $paymentCode,
        $uniqueId
    ) {
        $requestData = $this->getBaseTransactionRequest(
            $orderTransfer->getTotals()->getGrandTotal(),
            $paymentEntity->getCurrencyIso3Code(),
            $orderTransfer->getIdSalesOrder()
        );

        $this->addRequestData(
            $requestData,
            [
                ApiConstants::PAYMENT_CODE => $paymentCode,
                ApiConstants::IDENTIFICATION_REFERENCEID => $uniqueId,
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
        foreach ($additionalData as $fieldName => $value) {
            $requestData[$fieldName] = $value;
        }
    }

    /**
     * @param string $gender
     *
     * @throws \Spryker\Zed\Braintree\Business\Exception\GenderNotDefinedException
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
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
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

    /**
     * @return \Spryker\Shared\Library\Currency\CurrencyManager
     *
     * @todo: use currency/money bundle #989
     */
    protected function getCurrencyManager()
    {
        return CurrencyManager::getInstance();
    }

}
