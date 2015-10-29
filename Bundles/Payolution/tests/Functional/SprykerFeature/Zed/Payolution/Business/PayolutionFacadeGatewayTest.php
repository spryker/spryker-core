<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Functional\SprykerFeature\Zed\Payolution\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTableMap;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionRequestLog;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Propel\Runtime\Exception\PropelException;

/**
 * Note:
 * This test case doesn't use any mocks to prevent calling Payolution's
 * servers. Gateway environment is defined in the configuration files.
 * Test can be enabled/disabled using the $enableTests member variable.
 */
class PayolutionFacadeGatewayTest extends Test
{

    /**
     * @var SpySalesOrder
     */
    private $orderEntity;

    /**
     * @var SpyPaymentPayolution
     */
    private $paymentEntity;

    /**
     * @var bool
     */
    private $enableTests = false;

    /**
     * Test the saveOrderPayment() method of PayolutionFacade
     */
    public function testSaveOrderPayment()
    {
        if (false === $this->enableTests) {
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
        $paymentTransfer->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE)
            ->setClientIp('127.0.0.1')
            ->setDateOfBirth('1970-01-02')
            ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->setAddress($addressTransfer)
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE)
            ->setLanguageIso2Code('de')
            ->setCurrencyIso3Code('EUR');

        // PayolutionCheckoutConnector-HydrateOrderPlugin emulation
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());
        $orderTransfer->setPayolutionPayment($paymentTransfer);

        $facade = $this->getLocator()->payolution()->facade();
        $facade->saveOrderPayment($orderTransfer);

        $paymentEntity = $this->orderEntity->getSpyPaymentPayolutions()->getFirst();

        $this->assertInstanceOf('Orm\Zed\Payolution\Persistence\SpyPaymentPayolution', $paymentEntity);
        $this->assertEquals(Constants::ACCOUNT_BRAND_INVOICE, $paymentEntity->getAccountBrand());
        $this->assertEquals('127.0.0.1', $paymentEntity->getClientIp());
    }

    public function testPreCheckPayment()
    {
        if (false === $this->enableTests) {
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
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE)
            ->setAddress($addressTransfer)
            ->setLanguageIso2Code('de')
            ->setCurrencyIso3Code('EUR');

        $checkoutRequestTransfer = new CheckoutRequestTransfer();
        $checkoutRequestTransfer
            ->setCart($cartTransfer)
            ->setPayolutionPayment($paymentTransfer);

        $facade = $this->getLocator()->payolution()->facade();
        $response = $facade->preCheckPayment($checkoutRequestTransfer);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);
    }

    public function testPreAuthorizePayment()
    {
        if (false === $this->enableTests) {
            return;
        }

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $facade = $this->getLocator()->payolution()->facade();
        $response = $facade->preAuthorizePayment($this->paymentEntity->getIdPaymentPayolution());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);
    }

    public function testReAuthorizePayment()
    {
        if (false === $this->enableTests) {
            return;
        }

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $facade = $this->getLocator()->payolution()->facade();
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

    public function testRevertPayment()
    {
        if (false === $this->enableTests) {
            return;
        }

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $facade = $this->getLocator()->payolution()->facade();
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

    public function testRefundPayment()
    {
        if (false === $this->enableTests) {
            return;
        }

        $this->setBaseTestData();
        $this->setPaymentTestData();

        $facade = $this->getLocator()->payolution()->facade();
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
     * @throws PropelException
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
     * @throws PropelException
     */
    private function setPaymentTestData()
    {
        $this->paymentEntity = (new SpyPaymentPayolution())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE)
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

    /**
     * @return AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}
