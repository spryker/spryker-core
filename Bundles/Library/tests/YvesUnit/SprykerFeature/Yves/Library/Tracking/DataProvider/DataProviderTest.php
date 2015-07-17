<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\SprykerFeature\Yves\Library\Tracking\DataProvider;

use YvesUnit\SprykerFeature\Yves\Library\Tracking\Fixture\DataProvider\DummyDataProvider;
use SprykerFeature\Yves\Library\Tracking\DataProvider\OrderDataProvider;

/**
 * @group Tracking
 * @group DataProvider
 * @group CustomerDataProvider
 */
class DataProviderTest extends \PHPUnit_Framework_TestCase
{

    const KEY_A = 'keyA';
    const KEY_B = 'keyB';
    const VALUE_A = 'valueA';
    const VALUE_B = 'valueB';

    public function testGetNameShouldReturnCustomerDataProviderName()
    {
        $customerDataProvider = new DummyDataProvider();
        $this->assertSame(DummyDataProvider::DATA_PROVIDER_NAME, $customerDataProvider->getProviderName());
    }

    public function testAddDataShouldReturnInstanceOfDataProviderInterface()
    {
        $customerDataProvider = new DummyDataProvider();
        $result = $customerDataProvider->addData(self::KEY_A, self::VALUE_A);
        $this->assertInstanceOf('SprykerFeature\Yves\Library\Tracking\DataProvider\DataProviderInterface', $result);
    }

    public function testSetDataShouldReturnInstanceOfDataProviderInterface()
    {
        $customerDataProvider = new DummyDataProvider();
        $result = $customerDataProvider->setData([self::KEY_A => self::VALUE_A]);
        $this->assertInstanceOf('SprykerFeature\Yves\Library\Tracking\DataProvider\DataProviderInterface', $result);
    }

    public function testMergeDataProviderShouldReturnArrayWithDataFromBothProviders()
    {
        $customerDataProviderA = new DummyDataProvider();
        $customerDataProviderA->setData([self::KEY_A => self::VALUE_A]);

        $customerDataProviderB = new DummyDataProvider();
        $customerDataProviderB->setData([self::KEY_B => self::VALUE_B]);

        $customerDataProviderA->mergeDataProvider($customerDataProviderB);
        $data = $customerDataProviderA->getData();

        $this->assertTrue(array_key_exists(self::KEY_A, $data));
        $this->assertTrue(array_key_exists(self::KEY_B, $data));
    }

    public function testMergeDifferentDataProviderShouldThrowException()
    {
        $this->setExpectedException('SprykerFeature\Yves\Library\Tracking\DataProvider\DataProviderException');
        $customerDataProvider = new DummyDataProvider();
        $orderDataProvider = new OrderDataProvider();

        $customerDataProvider->mergeDataProvider($orderDataProvider);
    }

    public function testGetValueShouldReturnValueIfExists()
    {
        $customerDataProviderA = new DummyDataProvider();
        $customerDataProviderA->setData([self::KEY_A => self::VALUE_A]);
        $this->assertSame(self::VALUE_A, $customerDataProviderA->getValue(self::KEY_A));
    }

    public function testGetValueShouldReturnDefaultValueIfValueNotExists()
    {
        $customerDataProviderA = new DummyDataProvider();
        $this->assertSame(self::VALUE_A, $customerDataProviderA->getValue(self::KEY_A, self::VALUE_A));
    }

}
