<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaymentAuthorizationFailedTransfer;
use Generated\Shared\Transfer\PaymentAuthorizedTransfer;
use Generated\Shared\Transfer\PaymentCanceledTransfer;
use Generated\Shared\Transfer\PaymentCancellationFailedTransfer;
use Generated\Shared\Transfer\PaymentCancelReservationFailedTransfer;
use Generated\Shared\Transfer\PaymentCapturedTransfer;
use Generated\Shared\Transfer\PaymentCaptureFailedTransfer;
use Generated\Shared\Transfer\PaymentConfirmationFailedTransfer;
use Generated\Shared\Transfer\PaymentConfirmedTransfer;
use Generated\Shared\Transfer\PaymentRefundedTransfer;
use Generated\Shared\Transfer\PaymentRefundFailedTransfer;
use Generated\Shared\Transfer\PaymentReservationCanceledTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Payment\PaymentConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Payment\Dependency\PaymentStateMachineEvents;

class PaymentConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const PAYMENT_FOREIGN_PROVIDER = 'foreignPayments';

    /**
     * @uses \Spryker\Shared\Application\ApplicationConstants::BASE_URL_YVES
     *
     * @var string
     */
    protected const BASE_URL_YVES = 'APPLICATION:BASE_URL_YVES';

    /**
     * @var string
     */
    public const CHECKOUT_STRATEGY_EXPRESS_CHECKOUT = 'express-checkout';

    /**
     * @var string
     */
    public const PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_AUTHORIZATION = 'authorization';

    /**
     * @var string
     */
    public const PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_PRE_ORDER_PAYMENT = 'pre-order-payment';

    /**
     * @var string
     */
    public const PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_PRE_ORDER_CONFIRMATION = 'pre-order-confirmation';

    /**
     * @var string
     */
    public const PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_PRE_ORDER_CANCELLATION = 'pre-order-cancellation';

    /**
     * @var string
     */
    public const PRE_ORDER_PAYMENT_DATA_FIELD = 'preOrderPaymentData';

    /**
     * Specification:
     * - Returns a map of the payment methods and state machine's processes names.
     *
     * @api
     *
     * @example The format of returned array is:
     * [
     *    'PAYMENT_METHOD_A' => 'StateMachineProcess01',
     *    'PAYMENT_METHOD_B' => 'StateMachineProcess02',
     * ]
     *
     * @return array<string, string>
     */
    public function getPaymentStatemachineMappings(): array
    {
        return $this->get(PaymentConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING, []);
    }

    /**
     * Specification:
     * - Returns a map of the payment messages and state machine's processes names.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getSupportedOrderPaymentEventTransfersList(): array
    {
        return [
            PaymentAuthorizedTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_AUTHORIZATION_SUCCESSFUL,
            PaymentAuthorizationFailedTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_AUTHORIZATION_FAILED,

            PaymentCanceledTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_CANCEL_SUCCESSFUL,
            PaymentCancellationFailedTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_CANCEL_FAILED,

            PaymentCapturedTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_CAPTURE_SUCCESSFUL,
            PaymentCaptureFailedTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_CAPTURE_FAILED,

            PaymentRefundedTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_REFUND_SUCCESSFUL,
            PaymentRefundFailedTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_REFUND_FAILED,

            // @deprecated
            PaymentConfirmedTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_CONFIRMATION_SUCCESSFUL,
            PaymentConfirmationFailedTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_CONFIRMATION_FAILED,
            PaymentReservationCanceledTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_CANCEL_RESERVATION_SUCCESSFUL,
            PaymentCancelReservationFailedTransfer::class => PaymentStateMachineEvents::OMS_PAYMENT_CANCEL_RESERVATION_FAILED,
        ];
    }

    /**
     * Specification:
     * - Yves application route or full URL of the page where the customer is redirected after successful order payment.
     *
     * @api
     *
     * @return string
     */
    public function getSuccessRoute(): string
    {
        return '/payment/order-success';
    }

    /**
     * Specification:
     * - Yves application route or full URL of the page where the customer is taken when decided to cancel the order payment on the external payment page.
     *
     * @api
     *
     * @return string
     */
    public function getCancelRoute(): string
    {
        return '/payment/order-cancel';
    }

    /**
     * Specification:
     * - Yves application route or full URL of the page where the customer is taken when clicked Back button on the external payment page.
     *
     * @api
     *
     * @return string
     */
    public function getCheckoutSummaryPageRoute(): string
    {
        return '/checkout/summary';
    }

    /**
     * Specification:
     * - The URL to the payment page in Yves (relative) or another store front application (absolute), where the customer is redirected to make the payment.
     * - When empty value is set, the payment page is provided by a payment provider on an external site.
     *
     * @api
     *
     * @return string
     */
    public function getStoreFrontPaymentPage(): string
    {
        return '';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getBaseUrlYves(): string
    {
        return $this->get(static::BASE_URL_YVES);
    }

    /**
     * @api
     *
     * @example
     * [
     *     QuoteTransfer::ORDER_REFERENCE => 'orderReference',
     *     QuoteTransfer::ITEMS => [
     *         ItemTransfer::NAME => 'itemName',
     *         ItemTransfer::ABSTRACT_SKU => 'abstractSku',
     *     ],
     * ]
     *
     * @return array<mixed>
     */
    public function getQuoteFieldsForForeignPayment(): array
    {
        return [
            QuoteTransfer::ORDER_REFERENCE => 'orderReference',
            QuoteTransfer::PRICE_MODE => 'priceMode',
            QuoteTransfer::STORE => [
                StoreTransfer::NAME => 'storeName',
            ],
            QuoteTransfer::CUSTOMER => [
                CustomerTransfer::CUSTOMER_REFERENCE => 'customerReference',
                CustomerTransfer::EMAIL => 'customerEmail',
                CustomerTransfer::LOCALE => [
                    LocaleTransfer::LOCALE_NAME => 'localeName',
                ],
            ],
            QuoteTransfer::BILLING_ADDRESS => [
                AddressTransfer::ISO2_CODE => 'countryCode',
                AddressTransfer::FIRST_NAME => 'customerFirstName',
                AddressTransfer::LAST_NAME => 'customerLastName',
                AddressTransfer::SALUTATION => 'salutation',
                AddressTransfer::CITY => 'city',
                AddressTransfer::STATE => 'state',
                AddressTransfer::ADDRESS1 => 'address1',
                AddressTransfer::ADDRESS2 => 'address2',
                AddressTransfer::ADDRESS3 => 'address3',
                AddressTransfer::ZIP_CODE => 'zip',
                AddressTransfer::PHONE => 'phone',
            ],
            QuoteTransfer::SHIPPING_ADDRESS => [
                AddressTransfer::ISO2_CODE => 'shippingCountryCode',
                AddressTransfer::FIRST_NAME => 'shippingFirstName',
                AddressTransfer::LAST_NAME => 'shippingLastName',
                AddressTransfer::SALUTATION => 'shippingSalutation',
                AddressTransfer::CITY => 'shippingCity',
                AddressTransfer::STATE => 'shippingState',
                AddressTransfer::ADDRESS1 => 'shippingAddress1',
                AddressTransfer::ADDRESS2 => 'shippingAddress2',
                AddressTransfer::ADDRESS3 => 'shippingAddress3',
                AddressTransfer::ZIP_CODE => 'shippingZip',
                AddressTransfer::PHONE => 'shippingPhone',
            ],
            QuoteTransfer::CURRENCY => [
                CurrencyTransfer::CODE => 'currencyCode',
            ],
            QuoteTransfer::PAYMENT => [
                PaymentTransfer::AMOUNT => 'grandTotal',
                PaymentTransfer::PAYMENT_METHOD => 'paymentMethod',
                PaymentTransfer::PAYMENT_METHOD_NAME => 'paymentMethodName',
                PaymentTransfer::ADDITIONAL_PAYMENT_DATA => 'additionalPaymentData',
            ],
            QuoteTransfer::ITEMS => [
                ItemTransfer::ID_SALES_ORDER_ITEM => 'idSalesOrderItem',
                ItemTransfer::NAME => 'name',
                ItemTransfer::SKU => 'sku',
                ItemTransfer::QUANTITY => 'quantity',
                ItemTransfer::UNIT_PRICE => 'unitPrice',
            ],
            QuoteTransfer::EXPENSES => [
                ExpenseTransfer::TYPE => 'type',
                ExpenseTransfer::NAME => 'name',
                ExpenseTransfer::QUANTITY => 'quantity',
                ExpenseTransfer::UNIT_PRICE => 'unitPrice',
            ],
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTenantIdentifier(): string
    {
        return $this->get(PaymentConstants::TENANT_IDENTIFIER, '');
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isDebugEnabled(): bool
    {
        return $this->get(ApplicationConstants::ENABLE_APPLICATION_DEBUG, false);
    }
}
