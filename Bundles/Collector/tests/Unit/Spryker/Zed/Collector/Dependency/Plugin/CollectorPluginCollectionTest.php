<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\Collector\Dependency\Plugin;

use Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginCollection;
use Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginCollectionInterface;
use Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Collector\Exception\CollectorPluginNotFoundException;

/**
 * @group Spryker
 * @group Zed
 * @group Collector
 * @group CollectorPluginCollection
 */
class CollectorPluginCollectionTest extends \PHPUnit_Framework_TestCase
{

    const TYPE = 'type';

    /**
     * @return void
     */
    public function testAddPluginShouldReturnInstance()
    {
        $collectorPluginCollection = new CollectorPluginCollection();

        $result = $collectorPluginCollection->addPlugin($this->getCollectorPluginMock(), self::TYPE);

        $this->assertInstanceOf(CollectorPluginCollectionInterface::class, $result);
    }

    /**
     * @return void
     */
    public function testGetPluginShouldReturnPlugin()
    {
        $collectorPluginMock = $this->getCollectorPluginMock();

        $collectorPluginCollection = new CollectorPluginCollection();
        $collectorPluginCollection->addPlugin($collectorPluginMock, self::TYPE);

        $this->assertSame($collectorPluginMock, $collectorPluginCollection->getPlugin(self::TYPE));
    }

    /**
     * @return void
     */
    public function testGetPluginShouldThrowExceptionIfPluginNotRegistered()
    {
        $collectorPluginCollection = new CollectorPluginCollection();

        $this->setExpectedException(CollectorPluginNotFoundException::class);

        $collectorPluginCollection->getPlugin('undefined type');
    }

    /**
     * @return void
     */
    public function testHasPluginShouldReturnTrue()
    {
        $collectorPluginMock = $this->getCollectorPluginMock();

        $collectorPluginCollection = new CollectorPluginCollection();
        $collectorPluginCollection->addPlugin($collectorPluginMock, self::TYPE);

        $this->assertTrue($collectorPluginCollection->hasPlugin(self::TYPE));
    }

    /**
     * @return void
     */
    public function testHasShouldReturnFalse()
    {
        $collectorPluginCollection = new CollectorPluginCollection();

        $this->assertFalse($collectorPluginCollection->hasPlugin(self::TYPE));
    }

    /**
     * @return void
     */
    public function testGetTypesShouldReturnEmptyArrayIfNoPluginAttached()
    {
        $collectorPluginCollection = new CollectorPluginCollection();

        $this->assertInternalType('array', $collectorPluginCollection->getTypes());
    }

    /**
     * @return void
     */
    public function testGetTypesShouldReturnArray()
    {
        $collectorPluginMock = $this->getCollectorPluginMock();

        $collectorPluginCollection = new CollectorPluginCollection();
        $collectorPluginCollection->addPlugin($collectorPluginMock, self::TYPE);

        $types = $collectorPluginCollection->getTypes();
        $this->assertInternalType('array', $types);
        $this->assertCount(1, $types);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CollectorPluginInterface
     */
    private function getCollectorPluginMock()
    {
        return $this->getMock(CollectorPluginInterface::class);
    }
}
