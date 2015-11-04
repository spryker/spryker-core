<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\Method\invoice;

use Generated\Shared\Payolution\CheckoutRequestInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\AbstractPaymentMethod;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\ApiConstants;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

class Invoice extends AbstractPaymentMethod implements InvoiceInterface
{

    /**
     * @return string
     */
    public function getAccountBrand()
    {
        return ApiConstants::BRAND_INVOICE;
    }

    public function getTransactionChannel()
    {
        return $this->getConfig()->getTransactionChannelInvoice();
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
        $requestData = $this->getBaseRequestTransferForPayment(
            $paymentEntity,
            ApiConstants::PAYMENT_CODE_PRE_AUTHORIZATION,
            null);
        $this->addRequestData(
            $requestData,
            [
                ApiConstants::IDENTIFICATION_SHOPPERID => $paymentEntity->getSpySalesOrder()->getFkCustomer(),
                ApiConstants::CRITERION_CUSTOMER_LANGUAGE => $paymentEntity->getLanguageIso2Code(),
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
