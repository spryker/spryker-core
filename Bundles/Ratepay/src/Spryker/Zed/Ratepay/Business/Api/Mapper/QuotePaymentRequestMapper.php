<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
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
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $partialOrderTransfer;

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
     * @param \Generated\Shared\Transfer\OrderTransfer $partialOrderTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer|\Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer|\Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer $paymentData
     */
    public function __construct(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer,
        RatepayPaymentInitTransfer $ratepayPaymentInitTransfer,
        QuoteTransfer $quoteTransfer,
        OrderTransfer $partialOrderTransfer,
        $paymentData
    ) {
        $this->ratepayPaymentRequestTransfer = $ratepayPaymentRequestTransfer;
        $this->ratepayPaymentInitTransfer = $ratepayPaymentInitTransfer;
        $this->quoteTransfer = $quoteTransfer;
        $this->partialOrderTransfer = $partialOrderTransfer;
        $this->paymentData = $paymentData;
    }

    /**
     * @return void
     */
    public function map()
    {
        $this->mapPaymentInfo();
        $this->mapCustomer();
        $this->mapTotals();
        $this->mapExpenses();
        $this->mapAddresses();
        $this->mapBankAccountBic();
        $this->mapBankAccountIban();
        $this->mapDebitPayType();
        $this->mapInstallmentGrandTotalAmount();
        $this->mapInstallmentNumberRates();
        $this->mapInstallmentRate();
        $this->mapInstallmentLastRate();
        $this->mapInstallmentInterestRate();
        $this->mapInstallmentPaymentFirstDay();
        $this->mapBasketItems();
    }

    /**
     * @return void
     */
    protected function mapPaymentInfo()
    {
        $this->ratepayPaymentRequestTransfer
            ->setRatepayPaymentInit($this->ratepayPaymentInitTransfer)
            ->setPaymentType($this->paymentData->getPaymentType())
            ->setCurrencyIso3($this->paymentData->getCurrencyIso3());
    }

    /**
     * @return void
     */
    protected function mapCustomer()
    {
        $customerTransfer = $this->quoteTransfer->requireCustomer()->getCustomer();
        $this->ratepayPaymentRequestTransfer
            ->setCustomerEmail($customerTransfer->getEmail())
            ->setCustomerPhone($this->paymentData->getPhone())
            ->setCustomerAllowCreditInquiry($this->paymentData->getCustomerAllowCreditInquiry())
            ->setGender($this->paymentData->getGender())
            ->setDateOfBirth($this->paymentData->getDateOfBirth())
            ->setIpAddress($this->paymentData->getIpAddress());
    }

    /**
     * @return void
     */
    protected function mapTotals()
    {
        $totalsTransfer = $this->quoteTransfer->requireTotals()->getTotals();
        $this->ratepayPaymentRequestTransfer
            ->setGrandTotal($totalsTransfer->requireGrandTotal()->getGrandTotal())
            ->setExpenseTotal($totalsTransfer->requireExpenseTotal()->getExpenseTotal());
    }

    /**
     * @return void
     */
    protected function mapAddresses()
    {
        $billingAddress = $this->quoteTransfer->getBillingAddress();
        $shippingAddress = $this->quoteTransfer->getShippingAddress();

        $this->ratepayPaymentRequestTransfer
            ->setBillingAddress($billingAddress)
            ->setShippingAddress($shippingAddress)
            ->setBankAccountHolder($billingAddress->getFirstName() . " " . $billingAddress->getLastName());
    }

    /**
     * @return void
     */
    protected function mapExpenses()
    {
        $maxTaxRate = 0;
        $expenses = $this->quoteTransfer->getExpenses();

        foreach ($expenses as $expense) {
            $maxTaxRate = ($expense->getTaxRate() > $maxTaxRate) ? $expense->getTaxRate() : $maxTaxRate;
        }

        $this->ratepayPaymentRequestTransfer
            ->setShippingTaxRate($maxTaxRate);
    }

    /**
     * @return void
     */
    protected function mapBankAccountBic()
    {
        if (method_exists($this->paymentData, 'getBankAccountBic')) {
            $this->ratepayPaymentRequestTransfer
                ->setBankAccountBic($this->paymentData->getBankAccountBic());
        }
    }

    /**
     * @return void
     */
    protected function mapBankAccountIban()
    {
        if (method_exists($this->paymentData, 'getBankAccountIban')) {
            $this->ratepayPaymentRequestTransfer
                ->setBankAccountIban($this->paymentData->getBankAccountIban());
        }
    }

    /**
     * @return void
     */
    protected function mapDebitPayType()
    {
        if (method_exists($this->paymentData, 'getDebitPayType')) {
            $this->ratepayPaymentRequestTransfer
                ->setDebitPayType($this->paymentData->getDebitPayType());
        }
    }

    /**
     * @return void
     */
    protected function mapInstallmentNumberRates()
    {
        if (method_exists($this->paymentData, 'getInstallmentNumberRates')) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentNumberRates($this->paymentData->getInstallmentNumberRates());
        }
    }

    /**
     * @return void
     */
    protected function mapInstallmentRate()
    {
        if (method_exists($this->paymentData, 'getInstallmentRate')) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentRate($this->paymentData->getInstallmentRate());
        }
    }

    /**
     * @return void
     */
    protected function mapInstallmentLastRate()
    {
        if (method_exists($this->paymentData, 'getInstallmentLastRate')) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentLastRate($this->paymentData->getInstallmentLastRate());
        }
    }

    /**
     * @return void
     */
    protected function mapInstallmentInterestRate()
    {
        if (method_exists($this->paymentData, 'getInstallmentInterestRate')) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentInterestRate($this->paymentData->getInstallmentInterestRate());
        }
    }

    /**
     * @return void
     */
    protected function mapInstallmentPaymentFirstDay()
    {
        if (method_exists($this->paymentData, 'getInstallmentPaymentFirstDay')) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentPaymentFirstDay($this->paymentData->getInstallmentPaymentFirstDay());
        }
    }

    /**
     * @return void
     */
    protected function mapInstallmentGrandTotalAmount()
    {
        if ($this->quoteTransfer->getPayment()->getRatepayInstallment()) {
            $this->ratepayPaymentRequestTransfer
                ->setInstallmentGrandTotalAmount(
                    $this->quoteTransfer
                        ->getPayment()
                        ->getRatepayInstallment()
                        ->getInstallmentGrandTotalAmount()
                );
        }
    }

    /**
     * @return void
     */
    protected function mapBasketItems()
    {
        $basketItems = $this->quoteTransfer->getItems();
        $grouppedItems = [];
        $discountTaxRate = 0;
        foreach ($basketItems as $basketItem) {
            if (isset($grouppedItems[$basketItem->getGroupKey()])) {
                $grouppedItems[$basketItem->getGroupKey()]->setQuantity($grouppedItems[$basketItem->getGroupKey()]->getQuantity() + 1);
            } else {
                $grouppedItems[$basketItem->getGroupKey()] = clone $basketItem;
            }
            if ($discountTaxRate < $basketItem->getTaxRate()) { // take max taxRate
                $discountTaxRate = $basketItem->getTaxRate();
            }
        }
        $discountTotal = $this->partialOrderTransfer->getTotals()->getDiscountTotal();
        $this->ratepayPaymentRequestTransfer
            ->setDiscountTotal($discountTotal)
            ->setDiscountTaxRate($discountTaxRate);

        foreach ($grouppedItems as $basketItem) {
            $this->ratepayPaymentRequestTransfer->addItem($basketItem);
        }
    }
}
