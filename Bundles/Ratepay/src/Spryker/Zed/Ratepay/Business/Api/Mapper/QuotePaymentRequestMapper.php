<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;

class QuotePaymentRequestMapper extends BaseMapper
{

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentRequestTransfer
     */
    protected $ratepayPaymentRequestTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentInitTransfer
     */
    protected $ratepayPaymentInitTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer|\Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer|\Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer
     */
    protected $paymentData;

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer|\Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer|\Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer $paymentData
     */
    public function __construct(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer,
        RatepayPaymentInitTransfer $ratepayPaymentInitTransfer,
        QuoteTransfer $quoteTransfer,
        $paymentData
    ) {
        $this->ratepayPaymentRequestTransfer = $ratepayPaymentRequestTransfer;
        $this->ratepayPaymentInitTransfer = $ratepayPaymentInitTransfer;
        $this->quoteTransfer = $quoteTransfer;
        $this->paymentData = $paymentData;
    }

    /**
     * @return void
     */
    public function map()
    {
        $totalsTransfer = $this->quoteTransfer->requireTotals()->getTotals();
        $customerTransfer = $this->quoteTransfer->requireCustomer()->getCustomer();
        $billingAddress = $this->quoteTransfer->getBillingAddress();
        $shippingAddress = $this->quoteTransfer->getShippingAddress();
        $expenses = $this->quoteTransfer->getExpenses();

        $this->ratepayPaymentRequestTransfer
            ->setRatepayPaymentInit($this->ratepayPaymentInitTransfer)
            ->setGrandTotal($totalsTransfer->requireGrandTotal()->getGrandTotal())
            ->setExpenseTotal($totalsTransfer->requireExpenseTotal()->getExpenseTotal())
            ->setPaymentType($this->paymentData->getPaymentType())
            ->setCurrencyIso3($this->paymentData->getCurrencyIso3())

            ->setCustomerEmail($customerTransfer->getEmail())
            ->setCustomerPhone($this->paymentData->getPhone())
            ->setCustomerAllowCreditInquiry($this->paymentData->getCustomerAllowCreditInquiry())
            ->setGender($this->paymentData->getGender())
            ->setDateOfBirth($this->paymentData->getDateOfBirth())
            ->setIpAddress($this->paymentData->getIpAddress())

            ->setBillingAddress($billingAddress)
            ->setShippingAddress($shippingAddress)
        ;
        if (count($expenses)) {
            $this->ratepayPaymentRequestTransfer
                ->setShippingTaxRate($expenses[0]->getTaxRate());
        }
        if (method_exists($this->paymentData, 'getBankAccountHolder')) {
            $this->ratepayPaymentRequestTransfer
                ->setBankAccountHolder($this->paymentData->getBankAccountHolder());
        }
        if (method_exists($this->paymentData, 'getBankAccountBic')) {
            $this->ratepayPaymentRequestTransfer
                ->setBankAccountBic($this->paymentData->getBankAccountBic());
        }
        if (method_exists($this->paymentData, 'getBankAccountIban')) {
            $this->ratepayPaymentRequestTransfer
                ->setBankAccountIban($this->paymentData->getBankAccountIban());
        }
        if (method_exists($this->paymentData, 'getDebitPayType')) {
            $this->ratepayPaymentRequestTransfer
                ->setDebitPayType($this->paymentData->getDebitPayType());
        }
        if ($this->quoteTransfer->getPayment()->getRatepayInstallment()) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentGrandTotalAmount(
                    $this->quoteTransfer
                        ->getPayment()
                        ->getRatepayInstallment()
                        ->getInstallmentGrandTotalAmount()
                );
        }
        if (method_exists($this->paymentData, 'getInstallmentNumberRates')) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentNumberRates($this->paymentData->getInstallmentNumberRates());
        }
        if (method_exists($this->paymentData, 'getInstallmentRate')) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentRate($this->paymentData->getInstallmentRate());
        }
        if (method_exists($this->paymentData, 'getInstallmentLastRate')) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentLastRate($this->paymentData->getInstallmentLastRate());
        }
        if (method_exists($this->paymentData, 'getInstallmentInterestRate')) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentInterestRate($this->paymentData->getInstallmentInterestRate());
        }
        if (method_exists($this->paymentData, 'getInstallmentPaymentFirstDay')) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentPaymentFirstDay($this->paymentData->getInstallmentPaymentFirstDay());
        }

        $basketItems = $this->quoteTransfer->requireItems()->getItems();
        $grouppedItems = [];
        foreach ($basketItems as $basketItem) {
            if (isset($grouppedItems[$basketItem->getGroupKey()])) {
                $grouppedItems[$basketItem->getGroupKey()]->setQuantity($grouppedItems[$basketItem->getGroupKey()]->getQuantity() + 1);
            } else {
                $grouppedItems[$basketItem->getGroupKey()] = $basketItem;
            }
        }

        foreach ($grouppedItems as $basketItem) {
            $this->ratepayPaymentRequestTransfer->addItem($basketItem);
        }
    }

}
