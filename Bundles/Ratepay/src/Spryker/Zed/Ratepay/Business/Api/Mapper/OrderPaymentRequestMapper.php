<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;

class OrderPaymentRequestMapper extends BaseMapper
{

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentRequestTransfer
     */
    protected $ratepayPaymentRequestTransfer;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $orderEntity;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentInitTransfer
     */
    protected $ratepayPaymentInitTransfer;

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     */
    public function __construct(
        RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer,
        RatepayPaymentInitTransfer $ratepayPaymentInitTransfer,
        OrderTransfer $orderTransfer,
        SpySalesOrder $orderEntity
    ) {
        $this->ratepayPaymentRequestTransfer = $ratepayPaymentRequestTransfer;
        $this->ratepayPaymentInitTransfer = $ratepayPaymentInitTransfer;
        $this->orderTransfer = $orderTransfer;
        $this->orderEntity = $orderEntity;
    }

    /**
     * @return void
     */
    public function map()
    {
        if (
            $this->orderEntity->getSpyPaymentRatepays() &&
            count($this->orderEntity->getSpyPaymentRatepays()->getData())
        ) {
            /** @var \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $paymentRatepayEntity */
            $paymentRatepayEntity = $this->orderEntity->getSpyPaymentRatepays()->getData()[0];
            $this->ratepayPaymentRequestTransfer
                ->setPaymentType($paymentRatepayEntity->getPaymentType())
                ->setCurrencyIso3($paymentRatepayEntity->getCurrencyIso3())
                ->setCustomerPhone($paymentRatepayEntity->getPhone())
                ->setGender($paymentRatepayEntity->getGender())
                ->setDateOfBirth($paymentRatepayEntity->getDateOfBirth("Y-m-d"))
                ->setIpAddress($paymentRatepayEntity->getIpAddress())
                ->setCustomerAllowCreditInquiry($paymentRatepayEntity->getCustomerAllowCreditInquiry())

                ->setBankAccountHolder($paymentRatepayEntity->getBankAccountHolder())
                ->setBankAccountBic($paymentRatepayEntity->getBankAccountBic())
                ->setBankAccountIban($paymentRatepayEntity->getBankAccountIban())

                ->setDebitPayType($paymentRatepayEntity->getDebitPayType())
                ->setInstallmentGrandTotalAmount($paymentRatepayEntity->getInstallmentTotalAmount())
                ->setInstallmentNumberRates($paymentRatepayEntity->getInstallmentNumberRates())
                ->setInstallmentRate($paymentRatepayEntity->getInstallmentRate())
                ->setInstallmentLastRate($paymentRatepayEntity->getInstallmentLastRate())
                ->setInstallmentInterestRate($paymentRatepayEntity->getInstallmentInterestRate())
                ->setInstallmentPaymentFirstDay($paymentRatepayEntity->getInstallmentPaymentFirstDay())
            ;
        }

        $totalsTransfer = $this->orderTransfer->requireTotals()->getTotals();
        $billingAddress = $this->getAddressTransfer($this->orderEntity->getBillingAddress());
        $shippingAddress = $this->getAddressTransfer($this->orderEntity->getShippingAddress());

        $this->ratepayPaymentRequestTransfer
            ->setOrderId($this->orderEntity->getIdSalesOrder())
            ->setRatepayPaymentInit($this->ratepayPaymentInitTransfer)
            ->setGrandTotal($totalsTransfer->requireGrandTotal()->getGrandTotal())
            ->setExpenseTotal($totalsTransfer->requireExpenseTotal()->getExpenseTotal())

            ->setCustomerEmail($this->orderTransfer->getEmail())

            ->setBillingAddress($billingAddress)
            ->setShippingAddress($shippingAddress)
        ;
        $basketItems = $this->orderTransfer->requireItems()->getItems();
        foreach ($basketItems as $basketItem) {
            $this->ratepayPaymentRequestTransfer->addItem($basketItem);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $addressEntity
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getAddressTransfer(SpySalesOrderAddress $addressEntity)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setSalutation($addressEntity->getSalutation())
            ->setFirstName($addressEntity->getFirstName())
            ->setMiddleName($addressEntity->getMiddleName())
            ->setLastName($addressEntity->getLastName())
            ->setCompany($addressEntity->getCompany())
            ->setCity($addressEntity->getCity())
            ->setIso2Code($addressEntity->getCountry()->getIso2Code())
            ->setZipCode($addressEntity->getZipCode())
            ->setAddress1($addressEntity->getAddress1())
            ->setAddress2($addressEntity->getAddress2())
            ->setAddress3($addressEntity->getAddress3())
            ->setCellPhone($addressEntity->getCellPhone())
            ->setEmail($addressEntity->getEmail())
        ;

        return $addressTransfer;
    }

}
