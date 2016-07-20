<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\Collector\Business\Exporter;

use Spryker\Zed\Collector\Business\Exporter\AbstractExporter;
use Spryker\Zed\Collector\Business\Exporter\MarkerInterface;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\Business\Model\FailedResultInterface;
use Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginCollection;
use Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginCollectionInterface;
use Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

/**
 * @group Spryker
 * @group Zed
 * @group Collector
 * @group Business
 * @group AbstractExporter
 */
class AbstractExporterTest extends \PHPUnit_Framework_TestCase
{

    const TYPE = 'type';

    /**
     * @return void
     */
    public function testInstantiationWithoutCollectorPluginsShouldBuildCollectorPluginCollectionInternally()
    {
        $abstractExporterMock = $this->getAbstractExporterMock();

        $this->assertInstanceOf(CollectorPluginCollectionInterface::class, $abstractExporterMock->getCollectorPlugins());
    }

    /**
     * @return void
     */
    public function testInstantiationWithCollectorPluginsAsArrayShouldBuildCollectorPluginCollectionInternally()
    {
        $collectorPlugins = [
            self::TYPE => $this->getCollectorPluginMock()
        ];
        $abstractExporterMock = $this->getAbstractExporterMock($collectorPlugins);

        $collectorPluginCollection = $abstractExporterMock->getCollectorPlugins();

        $this->assertInstanceOf(CollectorPluginCollectionInterface::class, $collectorPluginCollection);
        $this->assertTrue($collectorPluginCollection->hasPlugin(self::TYPE));
    }

    /**
     * @return void
     */
    public function testInstantiationWithCollectorPluginCollection()
    {
        $collectorPluginCollection = new CollectorPluginCollection();
        $collectorPluginCollection->addPlugin($this->getCollectorPluginMock(), self::TYPE);
        $abstractExporterMock = $this->getAbstractExporterMock($collectorPluginCollection);

        $collectorPluginCollection = $abstractExporterMock->getCollectorPlugins();

        $this->assertInstanceOf(CollectorPluginCollectionInterface::class, $collectorPluginCollection);
        $this->assertTrue($collectorPluginCollection->hasPlugin(self::TYPE));
    }

    /**
     * @param \Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginCollectionInterface|array|null $collectorPlugins
     *
     * @return \Spryker\Zed\Collector\Business\Exporter\AbstractExporter
     */
    private function getAbstractExporterMock($collectorPlugins = null)
    {
        $arguments = [
            $this->getMock(TouchQueryContainerInterface::class),
            $this->getMock(ReaderInterface::class),
            $this->getMock(WriterInterface::class),
            $this->getMock(MarkerInterface::class),
            $this->getMock(FailedResultInterface::class),
            $this->getMock(BatchResultInterface::class),
            $this->getMock(TouchUpdaterInterface::class)
        ];

        if ($collectorPlugins !== null) {
            $arguments[] = $collectorPlugins;
        }

        return $this->getMockForAbstractClass(AbstractExporter::class, $arguments);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface
     */
    protected function getCollectorPluginMock()
    {
        return $this->getMock(CollectorPluginInterface::class);
    }

}
