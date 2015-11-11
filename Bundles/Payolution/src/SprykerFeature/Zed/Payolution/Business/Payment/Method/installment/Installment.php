<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Method\installment;

use Generated\Shared\Payolution\CheckoutRequestInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\AbstractPaymentMethod;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\ApiConstants;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;

class Installment extends AbstractPaymentMethod implements InstallmentInterface
{

    /**
     * @return string
     */
    public function getAccountBrand()
    {
        return ApiConstants::BRAND_INSTALLMENT;
    }

    /**
     * @return string
     */
    protected function getTransactionChannel()
    {
        return $this->getConfig()->getTransactionChannelInstallment();
    }

    /**
     * @return int
     */
    public function getMinGrandTotal()
    {
        return $this->getConfig()->getMinOrderGrandTotalInstallment();
    }

    /**
     * @return int
     */
    public function getMaxGrandTotal()
    {
        return $this->getConfig()->getMaxOrderGrandTotalInstallment();
    }

    /**
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return array
     */
    public function buildCalculationRequest(CheckoutRequestInterface $checkoutRequestTransfer)
    {
        return [
            ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_REQUEST_ELEMENT,
            ApiConstants::CALCULATION_XML_ELEMENT_ATTRIBUTES => [
                ApiConstants::CALCULATION_XML_REQUEST_VERSION_ATTRIBUTE => ApiConstants::CALCULATION_REQUEST_VERSION,
            ],
            [
                ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_SENDER_ELEMENT,
                ApiConstants::CALCULATION_XML_ELEMENT_VALUE => $this->getConfig()->getCalculationSender(),
            ],
            [
                ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_TRANSACTION_ELEMENT,
                ApiConstants::CALCULATION_XML_ELEMENT_ATTRIBUTES => [
                    ApiConstants::CALCULATION_XML_TRANSACTION_MODE_ATTRIBUTE => $this->getConfig()->getCalculationMode(),
                    ApiConstants::CALCULATION_XML_TRANSACTION_CHANNEL_ATTRIBUTE => $this->getConfig()->getCalculationChannel(),
                ],
                [
                    ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_IDENTIFICATION_ELEMENT,
                    [
                        ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_TRANSACTIONID_ELEMENT,
                        ApiConstants::CALCULATION_XML_ELEMENT_VALUE => uniqid('tran_'),
                    ],
                ],
                [
                    ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_PAYMENT_ELEMENT,
                    [
                        ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_OPERATION_TYPE_ELEMENT,
                        ApiConstants::CALCULATION_XML_ELEMENT_VALUE => ApiConstants::CALCULATION_OPERATION_TYPE,
                    ],
                    [
                        ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_PAYMENT_TYPE_ELEMENT,
                        ApiConstants::CALCULATION_XML_ELEMENT_VALUE => ApiConstants::CALCULATION_PAYMENT_TYPE,
                    ],
                    [
                        ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_PRESENTATION_ELEMENT,
                        [
                            ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_CURRENCY_ELEMENT,
                            ApiConstants::CALCULATION_XML_ELEMENT_VALUE => $checkoutRequestTransfer
                                ->getPayolutionPayment()
                                ->getCurrencyIso3Code(),
                        ],
                        [
                            ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_USAGE_ELEMENT,
                            ApiConstants::CALCULATION_XML_ELEMENT_VALUE => null,
                        ],
                        [
                            ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_AMOUNT_ELEMENT,
                            ApiConstants::CALCULATION_XML_ELEMENT_VALUE => $checkoutRequestTransfer
                                ->getCart()
                                ->getTotals()
                                ->getGrandTotal(),
                        ],
                        [
                            ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_VAT_ELEMENT,
                            ApiConstants::CALCULATION_XML_ELEMENT_VALUE => null,
                        ],
                    ],
                ],
                [
                    ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_ANALYSIS_ELEMENT,
                    [
                        ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_XML_CRITERION_ELEMENT,
                        ApiConstants::CALCULATION_XML_ELEMENT_ATTRIBUTES => [
                            ApiConstants::CALCULATION_XML_ELEMENT_NAME => ApiConstants::CALCULATION_TARGET_COUNTRY,
                        ],
                        ApiConstants::CALCULATION_XML_ELEMENT_VALUE => $checkoutRequestTransfer
                            ->getPayolutionPayment()
                            ->getAddress()
                            ->getIso2Code(),
                    ],
                ],

            ],
        ];
    }

    /**
     * @param CheckoutRequestInterface $checkoutRequestTransfer
     *
     * @return array
     */
    public function buildPreCheckRequest(CheckoutRequestInterface $checkoutRequestTransfer)
    {
        $payolutionTransfer = $checkoutRequestTransfer->getPayolutionPayment();
        $addressTransfer = $payolutionTransfer->getAddress();

        $requestData = $this->getBaseRequestTransfer(
            $checkoutRequestTransfer->getCart()->getTotals()->getGrandTotal(),
            $payolutionTransfer->getCurrencyIso3Code(),
            $isSalesOrder = null
        );
        $this->addRequestData(
            $requestData,
            [
                ApiConstants::PAYMENT_CODE => ApiConstants::PAYMENT_CODE_PRE_CHECK,
                ApiConstants::TRANSACTION_CHANNEL => $this->config->getTransactionChannelPreCheck(),
                ApiConstants::NAME_GIVEN => $addressTransfer->getFirstName(),
                ApiConstants::NAME_FAMILY => $addressTransfer->getLastName(),
                ApiConstants::NAME_TITLE => $addressTransfer->getSalutation(),
                ApiConstants::NAME_SEX => $this->mapGender($payolutionTransfer->getGender()),
                ApiConstants::NAME_BIRTHDATE => $payolutionTransfer->getDateOfBirth(),
                ApiConstants::ADDRESS_STREET => $this->formatAddress($addressTransfer),
                ApiConstants::ADDRESS_ZIP => $addressTransfer->getZipCode(),
                ApiConstants::ADDRESS_CITY => $addressTransfer->getCity(),
                ApiConstants::ADDRESS_COUNTRY => $addressTransfer->getIso2Code(),
                ApiConstants::CONTACT_EMAIL => $addressTransfer->getEmail(),
                ApiConstants::CONTACT_PHONE => $addressTransfer->getPhone(),
                ApiConstants::CONTACT_MOBILE => $addressTransfer->getCellPhone(),
                ApiConstants::CONTACT_IP => $payolutionTransfer->getClientIp(),
                ApiConstants::CRITERION_PRE_CHECK => 'TRUE',
                ApiConstants::CRITERION_CUSTOMER_LANGUAGE => $payolutionTransfer->getLanguageIso2Code(),
                ApiConstants::CRITERION_INSTALLMENT_AMOUNT => $payolutionTransfer->getInstallmentAmount(),
                ApiConstants::CRITERION_DURATION => $payolutionTransfer->getInstallmentDuration(),
                ApiConstants::CRITERION_ACCOUNT_HOLDER => $payolutionTransfer->getBankAccountHolder(),
                ApiConstants::CRITERION_ACCOUNT_BIC => $payolutionTransfer->getBankAccountBic(),
                ApiConstants::CRITERION_ACCOUNT_IBAN => $payolutionTransfer->getBankAccountIban(),
                ApiConstants::CRITERION_ACCOUNT_COUNTRY => $addressTransfer->getIso2Code(),
            ]
        );

        return $requestData;
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     *
     * @return array
     */
    public function buildPreAuthorizationRequest(SpyPaymentPayolution $paymentEntity)
    {
        $requestData = $this->getBaseRequestTransferForPayment($paymentEntity,
            ApiConstants::PAYMENT_CODE_PRE_AUTHORIZATION,
            null);
        $this->addRequestData(
            $requestData,
            [
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
                ApiConstants::IDENTIFICATION_SHOPPERID => $paymentEntity->getSpySalesOrder()->getFkCustomer(),
                ApiConstants::CRITERION_CUSTOMER_LANGUAGE => $paymentEntity->getLanguageIso2Code(),
                ApiConstants::CRITERION_INSTALLMENT_AMOUNT => $paymentEntity->getInstallmentAmount(),
                ApiConstants::CRITERION_DURATION => $paymentEntity->getInstallmentDuration(),
                ApiConstants::CRITERION_ACCOUNT_HOLDER => $paymentEntity->getBankAccountHolder(),
                ApiConstants::CRITERION_ACCOUNT_BIC => $paymentEntity->getBankAccountBic(),
                ApiConstants::CRITERION_ACCOUNT_IBAN => $paymentEntity->getBankAccountIban(),
                ApiConstants::CRITERION_ACCOUNT_COUNTRY => $paymentEntity->getCountryIso2Code(),
            ]
        );

        return $requestData;
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildReAuthorizationRequest(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
        return $this->getBaseRequestTransferForPayment($paymentEntity,
            ApiConstants::PAYMENT_CODE_RE_AUTHORIZATION,
            $uniqueId);
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildRevertRequest(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
        return $this->getBaseRequestTransferForPayment($paymentEntity,
            ApiConstants::PAYMENT_CODE_REVERSAL,
            $uniqueId);
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildCaptureRequest(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
        return $this->getBaseRequestTransferForPayment($paymentEntity,
            ApiConstants::PAYMENT_CODE_CAPTURE,
            $uniqueId);
    }

    /**
     * @param SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildRefundRequest(SpyPaymentPayolution $paymentEntity, $uniqueId)
    {
        return $this->getBaseRequestTransferForPayment($paymentEntity,
            ApiConstants::PAYMENT_CODE_REFUND,
            $uniqueId);
    }

}
