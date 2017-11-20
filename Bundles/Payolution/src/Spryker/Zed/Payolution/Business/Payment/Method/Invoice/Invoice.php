<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Payment\Method\Invoice;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Spryker\Zed\Payolution\Business\Payment\Method\AbstractPaymentMethod;
use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;

class Invoice extends AbstractPaymentMethod implements InvoiceInterface
{
    /**
     * @return string
     */
    public function getAccountBrand()
    {
        return ApiConstants::BRAND_INVOICE;
    }

    /**
     * @return string
     */
    protected function getTransactionChannel()
    {
        return $this->getConfig()->getTransactionChannelInvoice();
    }

    /**
     * @return int
     */
    public function getMinGrandTotal()
    {
        return $this->getConfig()->getMinOrderGrandTotalInvoice();
    }

    /**
     * @return int
     */
    public function getMaxGrandTotal()
    {
        return $this->getConfig()->getMaxOrderGrandTotalInvoice();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function buildPreCheckRequest(QuoteTransfer $quoteTransfer)
    {
        $payolutionTransfer = $quoteTransfer->getPayment()->getPayolution();
        $addressTransfer = $payolutionTransfer->getAddress();

        $requestData = $this->getBaseTransactionRequest(
            $quoteTransfer->getTotals()->getGrandTotal(),
            $payolutionTransfer->getCurrencyIso3Code()
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
                ApiConstants::CONTACT_EMAIL => $payolutionTransfer->getEmail(),
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
     *
     * @return array
     */
    public function buildPreAuthorizationRequest(OrderTransfer $orderTransfer, SpyPaymentPayolution $paymentEntity)
    {
        $requestData = $this->getBaseTransactionRequestForPayment(
            $orderTransfer,
            $paymentEntity,
            ApiConstants::PAYMENT_CODE_PRE_AUTHORIZATION,
            null
        );
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
                ApiConstants::CRITERION_PRE_CHECK_ID => $paymentEntity->getPreCheckId(),
                ApiConstants::CRITERION_CUSTOMER_LANGUAGE => $paymentEntity->getLanguageIso2Code(),
            ]
        );

        return $requestData;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildReAuthorizationRequest(
        OrderTransfer $orderTransfer,
        SpyPaymentPayolution $paymentEntity,
        $uniqueId
    ) {
        return $this->getBaseTransactionRequestForPayment(
            $orderTransfer,
            $paymentEntity,
            ApiConstants::PAYMENT_CODE_RE_AUTHORIZATION,
            $uniqueId
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildRevertRequest(
        OrderTransfer $orderTransfer,
        SpyPaymentPayolution $paymentEntity,
        $uniqueId
    ) {
        return $this->getBaseTransactionRequestForPayment(
            $orderTransfer,
            $paymentEntity,
            ApiConstants::PAYMENT_CODE_REVERSAL,
            $uniqueId
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildCaptureRequest(
        OrderTransfer $orderTransfer,
        SpyPaymentPayolution $paymentEntity,
        $uniqueId
    ) {
        return $this->getBaseTransactionRequestForPayment(
            $orderTransfer,
            $paymentEntity,
            ApiConstants::PAYMENT_CODE_CAPTURE,
            $uniqueId
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution $paymentEntity
     * @param string $uniqueId
     *
     * @return array
     */
    public function buildRefundRequest(
        OrderTransfer $orderTransfer,
        SpyPaymentPayolution $paymentEntity,
        $uniqueId
    ) {
        return $this->getBaseTransactionRequestForPayment(
            $orderTransfer,
            $paymentEntity,
            ApiConstants::PAYMENT_CODE_REFUND,
            $uniqueId
        );
    }
}
