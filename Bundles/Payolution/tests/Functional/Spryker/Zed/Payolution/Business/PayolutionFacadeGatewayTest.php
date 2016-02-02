<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Functional\Spryker\Zed\Payolution\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTableMap;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Payolution\Business\PayolutionFacade;

/**
 * Note:
 * This test case doesn't use any mocks to prevent calling Payolution's
 * servers. Gateway environment is defined in the configuration files.
 * Test can be enabled/disabled using the $enableTests member variable.
 */
class PayolutionFacadeGatewayTest extends Test
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
     * @var bool
     */
    private $enableTests = false;

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
        if ($this->enableTests === false) {
            return;
        }

        $this->setBaseTestData();

        $addressTransfer = (new AddressTransfer())
            ->setCity('Berlin')
            ->setZipCode('10623')
            ->setAddress1('Straße des 17. Juni 135')
            ->setFirstName('Jane')
            ->setLastName('Doe')
            ->setEmail('jane@family-doe.org')
            ->setIso2Code('de')
            ->setSalutation(SpyCustomerTableMap::COL_SALUTATION_MR);

        $paymentTransfer = new PayolutionPaymentTransfer();
        $paymentTransfer->setAccountBrand(ApiConstants::BRAND_INVOICE)
            ->setClientIp('127.0.0.1')
            ->setDateOfBirth('1970-01-02')
            ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->setAddress($addressTransfer)
            ->setAccountBrand(ApiConstants::BRAND_INVOICE)
            ->setLanguageIso2Code('de')
            ->setCurrencyIso3Code('EUR');

        // PayolutionCheckoutConnector-HydrateOrderPlugin emulation
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());
        $orderTransfer->setPayolutionPayment($paymentTransfer);

        $facade = $this->payolutionFacade;
        $facade->saveOrderPayment($orderTransfer);

        $paymentEntity = $this->orderEntity->getSpyPaymentPayolutions()->getFirst();

        $this->assertInstanceOf('Spryker\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution', $paymentEntity);
        $this->assertEquals(ApiConstants::BRAND_INVOICE, $paymentEntity->getAccountBrand());
        $this->assertEquals('127.0.0.1', $paymentEntity->getClientIp());
    }

    /**
     * @return void
     */
    public function testPreCheckPayment()
    {
        if ($this->enableTests === false) {
            return;
        }

        $this->setBaseTestData();

        $totals = new TotalsTransfer();
        $totals->setGrandTotal(10000);

        $cartTransfer = new CartTransfer();
        $cartTransfer->setTotals($totals);

        $addressTransfer = (new AddressTransfer())
            ->setCity('Berlin')
            ->setZipCode('10623')
            ->setAddress1('Straße des 17. Juni 135')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setEmail('john@doe.com')
            ->setIso2Code('de');

        $paymentTransfer = (new PayolutionPaymentTransfer())
            ->setGender('Male')
            ->setDateOfBirth('1970-01-01')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(ApiConstants::BRAND_INVOICE)
            ->setAddress($addressTransfer)
            ->setLanguageIso2Code('de')
            ->setCurrencyIso3Code('EUR');

        $checkoutRequestTransfer = new CheckoutRequestTransfer();
        $checkoutRequestTransfer
            ->setCart($cartTransfer)
            ->setPayolutionPayment($paymentTransfer);

        $facade = $this->payolutionFacade;
        $response = $facade->preCheckPayment($checkoutRequestTransfer);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);
    }

    /**
     * @return void
     */
    public function testPreAuthorizePayment()
    {
        if ($this->enableTests === false) {
            return;
        }

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $facade = $this->payolutionFacade;
        $response = $facade->preAuthorizePayment($this->paymentEntity->getIdPaymentPayolution());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);
    }

    /**
     * @return void
     */
    public function testReAuthorizePayment()
    {
        if ($this->enableTests === false) {
            return;
        }

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $facade = $this->payolutionFacade;
        $facade->preAuthorizePayment($this->paymentEntity->getIdPaymentPayolution());

        /** @var SpyPaymentPayolutionTransactionStatusLog $preAuthorizationStatusLogEntity */
        $preAuthorizationStatusLogEntity = $this
            ->paymentEntity
            ->getSpyPaymentPayolutionTransactionStatusLogs()
            ->getFirst();

        // Emulate increase of order price
        $orderEntity = $this->paymentEntity->getSpySalesOrder();
        $orderEntity
            ->setGrandTotal(20000)
            ->setSubtotal(20000)
            ->save();

        $responseTransfer = $facade->reAuthorizePayment($this->paymentEntity->getIdPaymentPayolution());
        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $responseTransfer);

        /* @var SpyPaymentPayolutionTransactionRequestLog $reAuthorizationRequestLogEntity */
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
        if ($this->enableTests === false) {
            return;
        }

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $facade = $this->payolutionFacade;
        $facade->preAuthorizePayment($this->paymentEntity->getIdPaymentPayolution());

        /** @var SpyPaymentPayolutionTransactionStatusLog $preAuthorizationStatusLogEntity */
        $preAuthorizationStatusLogEntity = $this
            ->paymentEntity
            ->getSpyPaymentPayolutionTransactionStatusLogs()
            ->getLast();

        $responseTransfer = $facade->revertPayment($this->paymentEntity->getIdPaymentPayolution());
        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $responseTransfer);

        /* @var SpyPaymentPayolutionTransactionRequestLog $reAuthorizationRequestLogEntity */
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
        if ($this->enableTests === false) {
            return;
        }

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $facade = $this->payolutionFacade;
        $facade->preAuthorizePayment($this->paymentEntity->getIdPaymentPayolution());
        $facade->capturePayment($this->paymentEntity->getIdPaymentPayolution());

        /* @var SpyPaymentPayolutionTransactionStatusLog $preAuthorizationStatusLogEntity */
        $captureStatusLogEntity = $this->paymentEntity->getSpyPaymentPayolutionTransactionStatusLogs()->getLast();

        $responseTransfer = $facade->refundPayment($this->paymentEntity->getIdPaymentPayolution());
        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $responseTransfer);

        /* @var SpyPaymentPayolutionTransactionRequestLog $reAuthorizationRequestLogEntity */
        $this->paymentEntity->clearSpyPaymentPayolutionTransactionRequestLogs();
        $refundRequestLogEntity = $this->paymentEntity->getSpyPaymentPayolutionTransactionRequestLogs()->getLast();

        $this->assertEquals(
            $captureStatusLogEntity->getIdentificationUniqueid(),
            $refundRequestLogEntity->getReferenceId()
        );
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    private function setBaseTestData()
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('de');

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
            ->setGrandTotal(10000)
            ->setSubtotal(10000)
            ->setIsTest(true)
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($billingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer)
            ->setOrderReference('foo-bar-baz-2');
        $this->orderEntity->save();
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
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
            ->setCountryIso2Code('de')
            ->setCity('Berlin')
            ->setStreet('Straße des 17. Juni 135')
            ->setZipCode('10623')
            ->setLanguageIso2Code('de')
            ->setCurrencyIso3Code('EUR');
        $this->paymentEntity->save();
    }

}
