<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Functional\SprykerFeature\Zed\Payolution\Business\Order;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Shared\Payolution\PayolutionApiConstants;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemState;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcess;
use SprykerFeature\Zed\Payolution\Business\Order\OrderManager;
use SprykerFeature\Zed\Payolution\Business\PayolutionDependencyContainer;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionQuery;
use SprykerFeature\Zed\Payolution\Persistence\Propel\Map\SpyPaymentPayolutionTableMap;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundle;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemBundleItem;

class OrderManagerTest extends Test
{

    public function testSaveOrderPaymentCreatesPersistentPaymentData()
    {
        $orderTransfer = $this->getOrderTransfer();
        $orderManager = new OrderManager($this->getPayolutionBusinessDependencyContainer());
        $orderManager->saveOrderPayment($orderTransfer);

        $paymentEntity = SpyPaymentPayolutionQuery::create()->findOneByFkSalesOrder($orderTransfer->getIdSalesOrder());
        $this->assertInstanceOf(
            'SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution',
            $paymentEntity
        );

        $paymentOrderItemEntities = $paymentEntity->getSpyPaymentPayolutionOrderItems();
        $this->assertCount(1, $paymentOrderItemEntities);
    }

    public function testSaveOrderPaymentHasAddressData()
    {
        $orderTransfer = $this->getOrderTransfer();
        $orderManager = new OrderManager($this->getPayolutionBusinessDependencyContainer());
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
            trim(sprintf(
                '%s %s %s',
                $addressTransfer->getAddress1(),
                $addressTransfer->getAddress2(),
                $addressTransfer->getAddress3()
            )),
            $paymentEntity->getStreet()
        );
    }

    /**
     * @return PayolutionDependencyContainer
     */
    private function getPayolutionBusinessDependencyContainer()
    {
        $factory = new Factory('Payolution');
        $locator = Locator::getInstance();
        $baseConfig = Config::getInstance();
        $config = new PayolutionConfig($baseConfig, $locator);
        $dependencyContainer = new PayolutionDependencyContainer($factory, $locator, $config);
        return $dependencyContainer;

    }

    /**
     * @return OrderTransfer
     */
    private function getOrderTransfer()
    {
        $orderEntity = $this->createOrderEntity();

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

        foreach ($orderEntity->getItems() as $orderItemEntity) {
            $itemTransfer = new ItemTransfer();
            $itemTransfer
                ->setName($orderItemEntity->getName())
                ->setQuantity($orderItemEntity->getQuantity())
                ->setPriceToPay($orderItemEntity->getPriceToPay())
                ->setFkSalesOrder($orderItemEntity->getFkSalesOrder())
                ->setIdSalesOrderItem($orderItemEntity->getIdSalesOrderItem());
            $orderTransfer->addItem($itemTransfer);
        }


        return $orderTransfer;
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return SpySalesOrder
     */
    private function createOrderEntity()
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

        $this->createOrderItemEntity($orderEntity->getIdSalesOrder());

        return $orderEntity;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return SpySalesOrderItem
     */
    private function createOrderItemEntity($idSalesOrder)
    {
        $stateEntity = $this->createOrderItemStateEntity();
        $processEntity = $this->createOrderProcessEntity();
        $bundleEntity = $this->createOrderItemBundleEntity();

        $orderItemEntity = new SpySalesOrderItem();
        $orderItemEntity
            ->setFkSalesOrder($idSalesOrder)
            ->setFkOmsOrderItemState($stateEntity->getIdOmsOrderItemState())
            ->setFkOmsOrderProcess($processEntity->getIdOmsOrderProcess())
            ->setFkSalesOrderItemBundle($bundleEntity->getIdSalesOrderItemBundle())
            ->setName('test product')
            ->setSku('1324354657687980')
            ->setGrossPrice(1000)
            ->setPriceToPay(100)
            ->setQuantity(1);
        $orderItemEntity->save();

        return $orderItemEntity;
    }

    /**
     * @return SpyOmsOrderItemState
     */
    private function createOrderItemStateEntity()
    {
        $stateEntity = new SpyOmsOrderItemState();
        $stateEntity->setName('test item state');
        $stateEntity->save();

        return $stateEntity;
    }

    /**
     * @return SpyOmsOrderProcess
     */
    private function createOrderProcessEntity()
    {
        $processEntity = new SpyOmsOrderProcess();
        $processEntity->setName('test process');
        $processEntity->save();

        return $processEntity;
    }

    /**
     * @return SpySalesOrderItemBundle
     */
    private function createOrderItemBundleEntity()
    {
        $bundleEntity = new SpySalesOrderItemBundle();
        $bundleEntity
            ->setName('test bundle')
            ->setSku('13243546')
            ->setGrossPrice(1000)
            ->setPriceToPay(1000)
            ->setBundleType('NonSplitBundle');
        $bundleEntity->save();

        $bundleItemEntity = new SpySalesOrderItemBundleItem();
        $bundleItemEntity
            ->setFkSalesOrderItemBundle($bundleEntity->getIdSalesOrderItemBundle())
            ->setName('test bundle item')
            ->setSku('13243546')
            ->setGrossPrice(1000)
            ->setVariety('Simple');
        $bundleItemEntity->save();

        return $bundleEntity;
    }

}
