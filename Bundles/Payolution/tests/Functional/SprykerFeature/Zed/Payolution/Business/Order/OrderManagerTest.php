<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Functional\SprykerFeature\Zed\Payolution\Business\Order;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use SprykerFeature\Shared\Payolution\PayolutionApiConstants;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Payolution\Business\Order\OrderManager;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionQuery;
use SprykerFeature\Zed\Payolution\Persistence\Propel\Map\SpyPaymentPayolutionTableMap;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;

class OrderManagerTest extends Test
{

    public function testSaveOrderPaymentHasAddressData()
    {
        $orderTransfer = $this->getOrderTransfer();
        $orderManager = new OrderManager();
        $orderManager->saveOrderPayment($orderTransfer);

        $paymentTransfer = $orderTransfer->getPayolutionPayment();
        $addressTransfer = $paymentTransfer->getAddress();
        /** @var SpyPaymentPayolution $paymentEntity */
        $paymentEntity = SpyPaymentPayolutionQuery::create()->findOneByFkSalesOrder($orderTransfer->getIdSalesOrder());
        $this->assertEquals($addressTransfer->getCity(), $paymentEntity->getCity());
        $this->assertEquals($addressTransfer->getIso2Code(), $paymentEntity->getCountryIso2Code());
        $this->assertEquals($addressTransfer->getZipCode(), $paymentEntity->getZipCode());
        $this->assertEquals($addressTransfer->getEmail(), $paymentEntity->getEmail());
        $this->assertEquals($addressTransfer->getFirstName(), $paymentEntity->getFirstName());
        $this->assertEquals($addressTransfer->getLastName(), $paymentEntity->getLastName());
        $this->assertEquals($addressTransfer->getSalutation(), $paymentEntity->getSalutation());
        $this->assertEquals($addressTransfer->getPhone(), $paymentEntity->getPhone());
        $this->assertEquals($addressTransfer->getCellPhone(), $paymentEntity->getCellPhone());
        $this->assertEquals(
            sprintf('%s %s', $addressTransfer->getAddress1(), $addressTransfer->getAddress2()),
            $paymentEntity->getStreet()
        );
    }

    /**
     * @return OrderTransfer
     */
    private function getOrderTransfer()
    {
        $orderEntity = $this->getOrderEntity();

        $paymentAddressTransfer = new AddressTransfer();
        $paymentAddressTransfer
            ->setIso2Code('de')
            ->setEmail('testst@tewst.com')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setCellPhone('+40 175 0815')
            ->setPhone('+30 0815')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setZipCode('10623')
            ->setSalutation(SpyPaymentPayolutionTableMap::COL_SALUTATION_MR)
            ->setCity('Berlin');

        $paymentTransfer = new PayolutionPaymentTransfer();
        $paymentTransfer
            ->setGender(SpyPaymentPayolutionTableMap::COL_GENDER_MALE)
            ->setDateOfBirth('1970-01-02')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(PayolutionApiConstants::BRAND_INVOICE)
            ->setLanguageIso2Code('de')
            ->setCurrencyIso3Code('EUR')
            ->setAddress($paymentAddressTransfer);

        $orderTransfer = new OrderTransfer();
        $orderTransfer
            ->setIdSalesOrder($orderEntity->getIdSalesOrder())
            ->setPayolutionPayment($paymentTransfer);

        return $orderTransfer;
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return SpySalesOrder
     */
    private function getOrderEntity()
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

        $orderEntity = (new SpySalesOrder())
            ->setEmail('john@doe.com')
            ->setGrandTotal(10000)
            ->setSubtotal(10000)
            ->setIsTest(true)
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($billingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer)
            ->setOrderReference('foo-bar-baz-2');
        $orderEntity->save();

        return $orderEntity;
    }

}
