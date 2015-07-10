<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\SprykerFeature\Yves\Library\Tracking;

use YvesUnit\SprykerFeature\Yves\Library\Tracking\Fixture\DataProvider\DummyDataProvider;
use YvesUnit\SprykerFeature\Yves\Library\Tracking\Fixture\Provider\HelloProvider;
use YvesUnit\SprykerFeature\Yves\Library\Tracking\Fixture\Provider\WorldProvider;
use SprykerFeature\Yves\Library\Tracking\PageTypeInterface;
use SprykerFeature\Yves\Library\Tracking\Tracking;

/**
 * @group Tracking
 * @group TrackingDataContainer
 */
class TrackingTest extends \PHPUnit_Framework_TestCase
{

    const HELLO = 'Hello';
    const WORLD = 'World';
    const FOO_PAGE_TYPE = 'fooPageType';
    const KEY_A = 'keyA';
    const KEY_B = 'keyB';
    const VALUE_B = 'valueB';
    const VALUE_A = 'valueA';

    public function setUp()
    {
        Tracking::getInstance()->reset();
    }

    public function testGetInstanceShouldReturnInstance()
    {
        $this->assertInstanceOf('SprykerFeature\Yves\Library\Tracking\Tracking', Tracking::getInstance());
    }

    public function testIsAvailableShouldReturnTrueOnProduction()
    {
        $tracking = Tracking::getInstance();
        \SprykerFeature_Shared_Library_Environment::setEnvironment(\SprykerFeature_Shared_Library_Environment::PRODUCTION);
        $this->assertTrue($tracking->isActive());
    }

    public function testIsAvailableShouldReturnTrueOnStaging()
    {
        $tracking = Tracking::getInstance();
        \SprykerFeature_Shared_Library_Environment::setEnvironment(\SprykerFeature_Shared_Library_Environment::STAGING);
        $this->assertTrue($tracking->isActive());
    }

    public function testIsAvailableShouldReturnTrueOnTesting()
    {
        $tracking = Tracking::getInstance();
        \SprykerFeature_Shared_Library_Environment::setEnvironment(\SprykerFeature_Shared_Library_Environment::TESTING);
        $this->assertTrue($tracking->isActive());
    }

    public function testIsAvailableShouldReturnFalseOnDevelopment()
    {
        $tracking = Tracking::getInstance();
        \SprykerFeature_Shared_Library_Environment::setEnvironment(\SprykerFeature_Shared_Library_Environment::DEVELOPMENT);
        $this->assertFalse($tracking->isActive());
    }

    public function testAddDataProviderShouldReturnInstanceOfContainer()
    {
        $tracking = Tracking::getInstance();
        $customerDataProvider = new DummyDataProvider();
        $this->assertInstanceOf('SprykerFeature\Yves\Library\Tracking\Tracking', $tracking->addDataProvider($customerDataProvider));
    }

    /**
     * @group mergeDataProvider
     */
    public function testAddDataProviderShouldMergeExistingDataProvider()
    {
        $tracking = Tracking::getInstance();

        $customerDataProviderA = new DummyDataProvider();
        $customerDataProviderA->setData([self::KEY_A => self::VALUE_A]);

        $customerDataProviderB = new DummyDataProvider();
        $customerDataProviderB->setData([self::KEY_B => self::VALUE_B]);

        $tracking->addDataProvider($customerDataProviderA);
        $tracking->addDataProvider($customerDataProviderB);

        $data = $tracking->getData();

        $this->assertArrayHasKey($customerDataProviderA->getProviderName(), $data);
        $this->assertTrue(array_key_exists(self::KEY_A, $data[$customerDataProviderA->getProviderName()]));
        $this->assertTrue(array_key_exists(self::KEY_B, $data[$customerDataProviderA->getProviderName()]));
    }

    public function testAddProviderShouldReturnInstanceOfDataContainer()
    {
        $tracking = Tracking::getInstance();
        $result = $tracking->addProvider(new HelloProvider());
        $this->assertInstanceOf('SprykerFeature\Yves\Library\Tracking\Tracking', $result);
    }

    public function testSetPageTypeShouldReturnInstanceOfDataContainer()
    {
        $tracking = Tracking::getInstance();
        $this->assertInstanceOf('SprykerFeature\Yves\Library\Tracking\Tracking', $tracking->setPageType(self::FOO_PAGE_TYPE));
    }

    public function testGetPageTypeShouldReturnDefaultPageTypeIfNoOneIsSet()
    {
        $tracking = Tracking::getInstance();
        $this->assertSame(PageTypeInterface::PAGE_TYPE_HOME, $tracking->getPageType());
    }

    public function testGetPageTypeShouldReturnPageTypeWhichWasSet()
    {
        $tracking = Tracking::getInstance();
        $tracking->setPageType(self::FOO_PAGE_TYPE);
        $this->assertSame(self::FOO_PAGE_TYPE, $tracking->getPageType());
    }

    /**
     * @group getTracking
     */
    public function testGetTrackingShouldReturnOnlyProviderForRequestedPageType()
    {
        $tracking = Tracking::getInstance();
        $providerHello = new HelloProvider();
        $tracking->addProvider($providerHello);

        $providerWorld = new WorldProvider();
        $tracking->addProvider($providerWorld);

        $tracking->setPageType(PageTypeInterface::PAGE_TYPE_HOME);

        \SprykerFeature_Shared_Library_Environment::setEnvironment(\SprykerFeature_Shared_Library_Environment::PRODUCTION);
        $trackingOutPut = $tracking->buildTracking()->getTrackingOutput(Tracking::POSITION_BEFORE_CLOSING_HEAD);
        $this->assertSame(implode(PHP_EOL, [self::HELLO, self::WORLD]), $trackingOutPut);
        \SprykerFeature_Shared_Library_Environment::setEnvironment(\SprykerFeature_Shared_Library_Environment::TESTING);
    }

    /**
     * @group getTracking
     */
    public function testGetTrackingShouldReturnAllPixelIfNoPageTypeWasSet()
    {
        $tracking = Tracking::getInstance();
        $providerHello = new HelloProvider();
        $tracking->addProvider($providerHello);

        $providerWorld = new WorldProvider();
        $tracking->addProvider($providerWorld);

        \SprykerFeature_Shared_Library_Environment::setEnvironment(\SprykerFeature_Shared_Library_Environment::PRODUCTION);
        $trackingOutPut = $tracking->buildTracking()->getTrackingOutput(Tracking::POSITION_BEFORE_CLOSING_HEAD);
        $this->assertSame(implode(PHP_EOL, [self::HELLO, self::WORLD]), $trackingOutPut);
        \SprykerFeature_Shared_Library_Environment::setEnvironment(\SprykerFeature_Shared_Library_Environment::TESTING);
    }

    /**
     * @group getTracking
     */
    public function testGetTrackingShouldReturnCommentedTrackingIfEnvIsDevelopment()
    {
        \SprykerFeature_Shared_Library_Environment::setEnvironment(\SprykerFeature_Shared_Library_Environment::DEVELOPMENT);
        $tracking = Tracking::getInstance();
        $providerHello = new HelloProvider();
        $tracking->addProvider($providerHello);

        $this->assertSame('<!-- ' . self::HELLO . ' -->', $tracking->buildTracking()->getTrackingOutput(Tracking::POSITION_BEFORE_CLOSING_HEAD));
    }

}
