<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Payment\Method;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTableMap;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Spryker\Zed\Payolution\Business\Exception\GenderNotDefinedException;
use Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMoneyInterface;
use Spryker\Zed\Payolution\PayolutionConfig;

abstract class AbstractPaymentMethod
{
    const PAYOLUTION_DATE_FORMAT = 'Y-m-d';

    /**
     * @var static string[]
     */
    protected static $genderMap = [
        SpyPaymentPayolutionTableMap::COL_GENDER_MALE => ApiConstants::SEX_MALE,
        SpyPaymentPayolutionTableMap::COL_GENDER_FEMALE => ApiConstants::SEX_FEMALE,
    ];

    /**
     * @var \Spryker\Zed\Payolution\PayolutionConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Payolution\PayolutionConfig $config
     * @param \Spryker\Zed\Payolution\Dependency\Facade\PayolutionToMoneyInterface $moneyFacade
     */
    public function __construct(PayolutionConfig $config, PayolutionToMoneyInterface $moneyFacade)
    {
        $this->config = $config;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @return \Spryker\Zed\Payolution\PayolutionConfig
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
    abstract protected function getTransactionChannel();

    /**
     * @return int
     */
    abstract public function getMinGrandTotal();

    /**
     * @return int
     */
    abstract public function getMaxGrandTotal();

    /**
     * @param int $grandTotal
     * @param string $currency
     * @param string|null $idOrder
     *
     * @return array
     */
    protected function getBaseTransactionRequest($grandTotal, $currency, $idOrder = null)
    {
        return [
            ApiConstants::ACCOUNT_BRAND => $this->getAccountBrand(),
            ApiConstants::TRANSACTION_MODE => $this->getConfig()->getTransactionMode(),
            ApiConstants::SECURITY_SENDER => $this->getConfig()->getTransactionSecuritySender(),
            ApiConstants::USER_LOGIN => $this->getConfig()->getTransactionUserLogin(),
            ApiConstants::USER_PWD => $this->getConfig()->getTransactionUserPassword(),
            ApiConstants::PRESENTATION_AMOUNT => $this->moneyFacade->convertIntegerToDecimal((int)$grandTotal),
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
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
     * @param string $paymentCode
     * @param string $uniqueId
     *
     * @return array
     */
    protected function getBaseTransactionRequestForPayment(
        OrderTransfer $orderTransfer,
        SpyPaymentPayolution $paymentEntity,
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
                ApiConstants::TRANSACTION_CHANNEL => $this->getTransactionChannel(),
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
     * @throws \Spryker\Zed\Payolution\Business\Exception\GenderNotDefinedException
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
}
