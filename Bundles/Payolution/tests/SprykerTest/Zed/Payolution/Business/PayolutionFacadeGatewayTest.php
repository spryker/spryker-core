<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payolution\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTableMap;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Payolution\Business\PayolutionFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Payolution
 * @group Business
 * @group Facade
 * @group PayolutionFacadeGatewayTest
 * Add your own group annotations below this line
 */
class PayolutionFacadeGatewayTest extends Unit
{
    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    private $orderEntity;

    /**
     * @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution
     */
    private $paymentEntity;

    /**
     * @var \Spryker\Zed\Payolution\Business\PayolutionFacade
     */
    private $payolutionFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->payolutionFacade = new PayolutionFacade();
    }

    /**
     * Test the saveOrderPayment() method of PayolutionFacade
     *
     * @return void
     */
    public function testSaveOrderPayment()
    {
        $this->markTestSkipped('Payolution request is too slow');

        $this->setBaseTestData();

        $addressTransfer = (new AddressTransfer())
            ->setCity('Berlin')
            ->setZipCode('10623')
            ->setAddress1('Straße des 17. Juni 135')
            ->setFirstName('Jane')
            ->setLastName('Doe')
            ->setEmail('jane@family-doe.org')
            ->setIso2Code('DE')
            ->setSalutation(SpyCustomerTableMap::COL_SALUTATION_MR);

        $payolutionPaymentTransfer = new PayolutionPaymentTransfer();
        $payolutionPaymentTransfer->setAccountBrand(ApiConstants::BRAND_INVOICE)
            ->setClientIp('127.0.0.1')
            ->setDateOfBirth('1970-01-02')
            ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->setAddress($addressTransfer)
            ->setAccountBrand(ApiConstants::BRAND_INVOICE)
            ->setLanguageIso2Code('DE')
            ->setCurrencyIso3Code('EUR')
            ->setEmail($addressTransfer->getEmail());

        // PayolutionCheckoutConnector-HydrateOrderPlugin emulation
        $quoteTransfer = new QuoteTransfer();

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPayolution($payolutionPaymentTransfer);

        $quoteTransfer->setPayment($paymentTransfer);

        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $saveOrderTransfer = new SaveOrderTransfer();
        $saveOrderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());
        $checkoutResponseTransfer->setSaveOrder($saveOrderTransfer);

        $facade = $this->payolutionFacade;
        $facade->saveOrderPayment($quoteTransfer, $checkoutResponseTransfer);

        $paymentEntity = $this->orderEntity->getSpyPaymentPayolutions()->getFirst();

        $this->assertInstanceOf(SpyPaymentPayolution::class, $paymentEntity);
        $this->assertEquals(ApiConstants::BRAND_INVOICE, $paymentEntity->getAccountBrand());
        $this->assertEquals('127.0.0.1', $paymentEntity->getClientIp());
    }

    /**
     * @return void
     */
    public function testPreCheckPayment()
    {
        $this->markTestSkipped('Payolution request is too slow');

        $this->setBaseTestData();

        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal(10000);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setTotals($totalsTransfer);

        $addressTransfer = (new AddressTransfer())
            ->setCity('Berlin')
            ->setZipCode('10623')
            ->setAddress1('Straße des 17. Juni 135')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setEmail('john@doe.com')
            ->setIso2Code('DE');

        $payolutionPaymentTransfer = (new PayolutionPaymentTransfer())
            ->setGender('Male')
            ->setDateOfBirth('1970-01-01')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(ApiConstants::BRAND_INVOICE)
            ->setAddress($addressTransfer)
            ->setLanguageIso2Code('DE')
            ->setCurrencyIso3Code('EUR');

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPayolution($payolutionPaymentTransfer);

        $quoteTransfer->setPayment($paymentTransfer);

        $facade = $this->payolutionFacade;
        $response = $facade->preCheckPayment($quoteTransfer);

        $this->assertInstanceOf(PayolutionTransactionResponseTransfer::class, $response);
    }

    /**
     * @return void
     */
    public function testPreAuthorizePayment()
    {
        $this->markTestSkipped('Payolution request is too slow');

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $orderTransfer = $this->createOrderTransferMock();

        $facade = $this->payolutionFacade;
        $response = $facade->preAuthorizePayment($orderTransfer, $this->paymentEntity->getIdPaymentPayolution());

        $this->assertInstanceOf(PayolutionTransactionResponseTransfer::class, $response);
    }

    /**
     * @return void
     */
    public function testReAuthorizePayment()
    {
        $this->markTestSkipped('Payolution request is too slow');

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $orderTransfer = $this->createOrderTransferMock();

        $facade = $this->payolutionFacade;
        $facade->preAuthorizePayment($orderTransfer, $this->paymentEntity->getIdPaymentPayolution());

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog $preAuthorizationStatusLogEntity */
        $preAuthorizationStatusLogEntity = $this
            ->paymentEntity
            ->getSpyPaymentPayolutionTransactionStatusLogs()
            ->getFirst();

        $responseTransfer = $facade->reAuthorizePayment($orderTransfer, $this->paymentEntity->getIdPaymentPayolution());
        $this->assertInstanceOf(PayolutionTransactionResponseTransfer::class, $responseTransfer);

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog $reAuthorizationRequestLogEntity */
        $this->paymentEntity->clearSpyPaymentPayolutionTransactionRequestLogs();
        $reAuthorizationRequestLogEntity = $this
            ->paymentEntity
            ->getSpyPaymentPayolutionTransactionRequestLogs()
            ->getLast();

        $this->assertEquals(
            $preAuthorizationStatusLogEntity->getIdentificationUniqueid(),
            $reAuthorizationRequestLogEntity->getReferenceId()
        );

        // @todo CD-408 Test $responseTransfer fields
    }

    /**
     * @return void
     */
    public function testRevertPayment()
    {
        $this->markTestSkipped('Payolution request is too slow');

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $orderTransfer = $this->createOrderTransferMock();

        $facade = $this->payolutionFacade;
        $facade->preAuthorizePayment($orderTransfer, $this->paymentEntity->getIdPaymentPayolution());

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog $preAuthorizationStatusLogEntity */
        $preAuthorizationStatusLogEntity = $this
            ->paymentEntity
            ->getSpyPaymentPayolutionTransactionStatusLogs()
            ->getLast();

        $responseTransfer = $facade->revertPayment($orderTransfer, $this->paymentEntity->getIdPaymentPayolution());
        $this->assertInstanceOf(PayolutionTransactionResponseTransfer::class, $responseTransfer);

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog $reAuthorizationRequestLogEntity */
        $this->paymentEntity->clearSpyPaymentPayolutionTransactionRequestLogs();
        $revertRequestLogEntity = $this->paymentEntity->getSpyPaymentPayolutionTransactionRequestLogs()->getLast();

        $this->assertEquals(
            $preAuthorizationStatusLogEntity->getIdentificationUniqueid(),
            $revertRequestLogEntity->getReferenceId()
        );
    }

    /**
     * @return void
     */
    public function testRefundPayment()
    {
        $this->markTestSkipped('Payolution request is too slow');

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $orderTransfer = $this->createOrderTransferMock();

        $facade = $this->payolutionFacade;
        $facade->preAuthorizePayment($orderTransfer, $this->paymentEntity->getIdPaymentPayolution());
        $facade->capturePayment($orderTransfer, $this->paymentEntity->getIdPaymentPayolution());

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog $preAuthorizationStatusLogEntity */
        $preAuthorizationStatusLogEntity = $this
            ->paymentEntity
            ->getSpyPaymentPayolutionTransactionStatusLogs()
            ->getFirst();

        $responseTransfer = $facade->refundPayment($orderTransfer, $this->paymentEntity->getIdPaymentPayolution());
        $this->assertInstanceOf(PayolutionTransactionResponseTransfer::class, $responseTransfer);

        /** @var \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog $reAuthorizationRequestLogEntity */
        $this->paymentEntity->clearSpyPaymentPayolutionTransactionRequestLogs();
        $refundRequestLogEntity = $this->paymentEntity->getSpyPaymentPayolutionTransactionRequestLogs()->getLast();

        $this->assertEquals(
            $preAuthorizationStatusLogEntity->getIdentificationUniqueid(),
            $refundRequestLogEntity->getReferenceId()
        );
    }

    /**
     * @return void
     */
    private function setBaseTestData()
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('DE');

        $billingAddress = (new SpySalesOrderAddress())
            ->setFkCountry($country->getIdCountry())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setAddress1('Straße des 17. Juni 135')
            ->setCity('Berlin')
            ->setZipCode('10623');
        $billingAddress->save();

        $customer = (new SpyCustomer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john@doe.com')
            ->setDateOfBirth('1970-01-01')
            ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->setCustomerReference('payolution-pre-authorization-test');
        $customer->save();

        $this->orderEntity = (new SpySalesOrder())
            ->setEmail('john@doe.com')
            ->setIsTest(true)
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($billingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer)
            ->setOrderReference('foo-bar-baz-2');
        $this->orderEntity->save();
    }

    /**
     * @return void
     */
    private function setPaymentTestData()
    {
        $this->paymentEntity = (new SpyPaymentPayolution())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setAccountBrand(ApiConstants::BRAND_INVOICE)
            ->setClientIp('127.0.0.1')
            ->setFirstName('Jane')
            ->setLastName('Doe')
            ->setDateOfBirth('1970-01-02')
            ->setEmail('jane@family-doe.org')
            ->setGender(SpyPaymentPayolutionTableMap::COL_GENDER_MALE)
            ->setSalutation(SpyPaymentPayolutionTableMap::COL_SALUTATION_MR)
            ->setCountryIso2Code('DE')
            ->setCity('Berlin')
            ->setStreet('Straße des 17. Juni 135')
            ->setZipCode('10623')
            ->setLanguageIso2Code('DE')
            ->setCurrencyIso3Code('EUR');
        $this->paymentEntity->save();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransferMock()
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer->setGrandTotal(10000);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($this->orderEntity->toArray(), true);
        $orderTransfer->setTotals($totalsTransfer);
        return $orderTransfer;
    }
}
