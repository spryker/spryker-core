<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Api\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Generated\Shared\Transfer\RatepayRequestTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory;
use Spryker\Zed\Ratepay\Business\Api\Mapper\QuotePaymentRequestMapper;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Api
 * @group Mapper
 * @group AbstractMapperTest
 * Add your own group annotations below this line
 */
abstract class AbstractMapperTest extends Unit
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Mapper\MapperFactory
     */
    protected $mapperFactory;

    /**
     * @var \Generated\Shared\Transfer\RatepayRequestTransfer
     */
    protected $requestTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestTransfer = new RatepayRequestTransfer();
        $this->mapperFactory = new MapperFactory($this->requestTransfer);
    }

    /**
     * @return void
     */
    abstract public function testMapper();

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function mockQuoteTransfer()
    {
        $total = new TotalsTransfer();
        $total->setGrandTotal(1800)
            ->setExpenseTotal(0)
            ->setDiscountTotal(200);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setTotals($total)
            ->setCustomer($this->mockCustomerTransfer())
            ->setBillingAddress($this->mockAddressTransfer())
            ->setShippingAddress($this->mockAddressTransfer())
            ->setPayment(new PaymentTransfer());

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function mockOrderTransfer()
    {
        $total = new TotalsTransfer();
        $total->setGrandTotal(1800)
            ->setExpenseTotal(0);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setTotals($total)
            ->setCustomer($this->mockCustomerTransfer())
            ->setBillingAddress($this->mockAddressTransfer())
            ->setShippingAddress($this->mockAddressTransfer());

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function mockPartialOrderTransfer()
    {
        $total = new TotalsTransfer();
        $total->setGrandTotal(1800)
            ->setExpenseTotal(0);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setTotals($total);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentElvTransfer|\Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer|\Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer|\Generated\Shared\Transfer\RatepayPaymentPrepaymentTransfer|null $paymentData
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentRequestTransfer
     */
    protected function mockRatepayPaymentRequestTransfer($paymentData = null, $quoteTransfer = null)
    {
        if ($paymentData === null) {
            $paymentData = $this->mockPaymentElvTransfer();
        }
        if ($quoteTransfer === null) {
            $quoteTransfer = $this->mockQuoteTransfer();
        }
        $partialOrderTransfer = $this->mockPartialOrderTransfer();

        $ratepayPaymentRequestTransfer = new RatepayPaymentRequestTransfer();
        $ratepayPaymentInitTransfer = new RatepayPaymentInitTransfer();
        $quotePaymentRequestMapper = new QuotePaymentRequestMapper(
            $ratepayPaymentRequestTransfer,
            $ratepayPaymentInitTransfer,
            $quoteTransfer,
            $partialOrderTransfer,
            $paymentData
        );
        $quotePaymentRequestMapper->map();
        $ratepayPaymentRequestTransfer->setDiscountTotal(200);

        return $ratepayPaymentRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function mockCustomerTransfer()
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail('email@site.com');

        return $customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function mockAddressTransfer()
    {
        $address = new AddressTransfer();
        $address->setFirstName('fn')
            ->setLastName('ln')
            ->setPhone('0491234567')
            ->setCity('Berlin')
            ->setIso2Code('iso2')
            ->setAddress1('addr1')
            ->setAddress2('addr2')
            ->setZipCode('zip');
        return $address;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentElvTransfer
     */
    protected function mockPaymentElvTransfer()
    {
        $ratepayPaymentTransfer = new RatepayPaymentElvTransfer();
        $ratepayPaymentTransfer->setBankAccountIban('iban')
            ->setBankAccountBic('bic')
            ->setBankAccountHolder('holder')
            ->setCurrencyIso3('iso3')
            ->setGender('m')
            ->setPhone('123456789')
            ->setDateOfBirth('1980-01-02')
            ->setIpAddress('127.1.2.3')
            ->setCustomerAllowCreditInquiry(true)
            ->setPaymentType('invoice');

        return $ratepayPaymentTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RatepayPaymentInstallmentTransfer
     */
    protected function mockRatepayPaymentInstallmentTransfer()
    {
        $ratepayPaymentInstallmentTransfer = new RatepayPaymentInstallmentTransfer();
        $ratepayPaymentInstallmentTransfer
            ->setInstallmentCalculationType('calculation-by-rate')
            ->setInstallmentGrandTotalAmount(12570)
            ->setInstallmentRate(1200)
            ->setInstallmentInterestRate(14)
            ->setInstallmentLastRate(1450)
            ->setInstallmentMonth(3)
            ->setInterestRate(14)
            ->setInterestMonth(3)
            ->setInstallmentNumberRates(3)
            ->setInstallmentPaymentFirstDay(28)
            ->setInstallmentCalculationStart('2016-05-15')

            ->setBankAccountIban('iban')
            ->setBankAccountBic('bic')
            ->setBankAccountHolder('holder')
            ->setCurrencyIso3('iso3')
            ->setGender('m')
            ->setPhone('123456789')
            ->setDateOfBirth('1980-01-02')
            ->setIpAddress('127.1.2.3')
            ->setCustomerAllowCreditInquiry(true)
            ->setPaymentType('invoice')
            ->setDebitPayType('invoice');

        return $ratepayPaymentInstallmentTransfer;
    }

}
