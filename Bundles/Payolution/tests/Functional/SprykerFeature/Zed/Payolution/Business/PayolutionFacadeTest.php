<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Functional\SprykerFeature\Zed\Payolution\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Account;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Payment;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;

class PayolutionFacadeTest extends Test
{

    /**
     * @var SpySalesOrder
     */
    private $orderEntity;

    /**
     * Test the saveOrderPayment() method of PayolutionFacade
     */
    public function testSaveOrderPayment()
    {
        $this->setTestData();

        $paymentTransfer = new PayolutionPaymentTransfer();
        $paymentTransfer->setFkSalesOrder($this->orderEntity->getIdSalesOrder());
        $paymentTransfer->setPaymentCode(Payment::CODE_PRE_AUTHORIZATION);
        $paymentTransfer->setTransactionId('dfgdsgdfg');
        $paymentTransfer->setAccountBrand(Account::BRAND_INVOICE);
        $paymentTransfer->setClientIp('127.0.0.1');

        // PayolutionCheckoutConnector-HydrateOrderPlugin emulation
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());
        $orderTransfer->setPayolutionPayment($paymentTransfer);

        // PayolutionCheckoutConnector-SaveOrderPlugin emulation
        $facade = $this->getLocator()->payolution()->facade();
        $facade->saveOrderPayment($orderTransfer);

        $paymentEntity = $this->orderEntity->getSpyPaymentPayolutions()->getFirst();

        $this->assertInstanceOf('SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution', $paymentEntity);
        $this->assertEquals(Payment::CODE_PRE_AUTHORIZATION, $paymentEntity->getPaymentCode());
        $this->assertEquals('dfgdsgdfg', $paymentEntity->getTransactionId());
        $this->assertEquals(Account::BRAND_INVOICE, $paymentEntity->getAccountBrand());
        $this->assertEquals('127.0.0.1', $paymentEntity->getClientIp());
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function testPreAuthorizePayment()
    {
        $this->setTestData();

        $paymentEntity = (new SpyPaymentPayolution())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setPaymentCode(Payment::CODE_PRE_AUTHORIZATION)
            ->setAccountBrand(Account::BRAND_INVOICE)
            ->setClientIp('127.0.0.1')
            ->setTransactionId('asdökjfhsdkfa');
        $paymentEntity->save();

        $facade = $this->getLocator()->payolution()->facade();
        $response = $facade->preAuthorizePayment($paymentEntity->getIdPaymentPayolution());

        $this->assertInstanceOf('SprykerFeature\Zed\Payolution\Business\Api\Response\PreAuthorizationResponse', $response);

        // @todo CD-408 Assert persistent data
        // @todo CD-408 Assert response data
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function setTestData()
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
            ->setGrandTotal(100.00)
            ->setSubtotal(100.00)
            ->setIsTest(true)
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($billingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer)
            ->setOrderReference('foo-bar-baz-2');
        $this->orderEntity->save();
    }

    /**
     * @return AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}
