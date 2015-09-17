<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Functional\SprykerFeature\Zed\Payolution\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionRequestLog;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionTransactionStatusLog;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;

class PayolutionFacadeTest extends Test
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
     * Test the saveOrderPayment() method of PayolutionFacade
     */
    public function testSaveOrderPayment()
    {
        $this->setBaseTestData();

        $paymentTransfer = new PayolutionPaymentTransfer();
        $paymentTransfer->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE)
            ->setClientIp('127.0.0.1')
            ->setFirstName('Jane')
            ->setLastName('Doe')
            ->setBirthdate('1970-01-02')
            ->setEmail('jane@family-doe.org')
            ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->setSalutation(SpyCustomerTableMap::COL_SALUTATION_MR);

        // PayolutionCheckoutConnector-HydrateOrderPlugin emulation
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());
        $orderTransfer->setPayolutionPayment($paymentTransfer);

        $facade = $this->getLocator()->payolution()->facade();
        $facade->saveOrderPayment($orderTransfer);

        $paymentEntity = $this->orderEntity->getSpyPaymentPayolutions()->getFirst();

        $this->assertInstanceOf('SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution', $paymentEntity);
        $this->assertEquals(Constants::ACCOUNT_BRAND_INVOICE, $paymentEntity->getAccountBrand());
        $this->assertEquals('127.0.0.1', $paymentEntity->getClientIp());
    }

    public function testPreCheckPayment()
    {
        $this->setBaseTestData();

        $totalsTransfer = (new TotalsTransfer())->setGrandTotal(10000);

        $addressTransfer = (new AddressTransfer())
            ->setCity('Berlin')
            ->setZipCode('10623')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135');

        $paymentTransfer = (new PayolutionPaymentTransfer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setGender('Male')
            ->setSalutation('Mr')
            ->setBirthdate('1970-01-01')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE);

        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setPayolutionPayment($paymentTransfer)
            ->setBillingAddress($addressTransfer)
            ->setTotals($totalsTransfer);

        $facade = $this->getLocator()->payolution()->facade();
        $response = $facade->preCheckPayment($orderTransfer);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);
    }

    public function testPreAuthorizePayment()
    {
        $this->setBaseTestData();
        $this->setPaymentTestData();

        $facade = $this->getLocator()->payolution()->facade();
        $response = $facade->preAuthorizePayment($this->paymentEntity->getIdPaymentPayolution());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);
    }

    public function testReAuthorizePayment()
    {
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
        $this->setBaseTestData();
        $this->setPaymentTestData();

        $facade = $this->getLocator()->payolution()->facade();
        $facade->preAuthorizePayment($this->paymentEntity->getIdPaymentPayolution());
        $facade->capturePayment($this->paymentEntity->getIdPaymentPayolution());

        /** @var SpyPaymentPayolutionTransactionStatusLog $preAuthorizationStatusLogEntity */
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
     */
    private function setPaymentTestData()
    {
        $this->paymentEntity = (new SpyPaymentPayolution())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE)
            ->setClientIp('127.0.0.1')
            ->setFirstName('Jane')
            ->setLastName('Doe')
            ->setBirthdate('1970-01-02')
            ->setEmail('jane@family-doe.org')
            ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->setSalutation(SpyCustomerTableMap::COL_SALUTATION_MR);
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
