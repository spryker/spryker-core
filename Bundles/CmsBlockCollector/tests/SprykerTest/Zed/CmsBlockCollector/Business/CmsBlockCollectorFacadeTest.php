<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockCollector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\CmsBlockCollector\Business\CmsBlockCollectorBusinessFactory;
use Spryker\Zed\CmsBlockCollector\Business\CmsBlockCollectorFacade;
use Spryker\Zed\CmsBlockCollector\Business\Collector\Storage\CmsBlockCollector;
use Spryker\Zed\Collector\Business\CollectorFacadeInterface;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsBlockCollector
 * @group Business
 * @group Facade
 * @group CmsBlockCollectorFacadeTest
 * Add your own group annotations below this line
 */
class CmsBlockCollectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsBlockCollector\CmsBlockCollectorBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CmsBlockCollector\Business\CmsBlockCollectorFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $cmsBlockCollectorFacadeMock;

    /**
     * @var \Spryker\Zed\CmsBlockCollector\Business\CmsBlockCollectorBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $cmsBlockCollectorBusinessFactoryMock;

    /**
     * @var \Spryker\Zed\Collector\Business\CollectorFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectorFacadeMock;

    /**
     * @var \Spryker\Zed\CmsBlockCollector\Business\Collector\Storage\CmsBlockCollector|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $cmsBlockCollector;

    /**
     * @uses CmsBlockCollectorFacadeInterface::getFactory()
     * @uses CmsBlockCollectorBusinessFactory::getCollectorFacade()
     * @uses CmsBlockCollectorBusinessFactory::createStorageCmsBlockCollector()
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->collectorFacadeMock = $this
            ->getMockBuilder(CollectorFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cmsBlockCollector = $this
            ->getMockBuilder(CmsBlockCollector::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cmsBlockCollectorBusinessFactoryMock = $this
            ->getMockBuilder(CmsBlockCollectorBusinessFactory::class)
            ->setMethods(
                [
                    'getCollectorFacade',
                    'createStorageCmsBlockCollector',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->cmsBlockCollectorBusinessFactoryMock
            ->expects($this->any())
            ->method('createStorageCmsBlockCollector')
            ->willReturn($this->cmsBlockCollector);

        $this->cmsBlockCollectorBusinessFactoryMock
            ->expects($this->any())
            ->method('getCollectorFacade')
            ->willReturn($this->collectorFacadeMock);

        $this->cmsBlockCollectorFacadeMock = $this
            ->getMockBuilder(CmsBlockCollectorFacade::class)
            ->setMethods(
                [
                    'getFactory',
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->cmsBlockCollectorFacadeMock
            ->expects($this->any())
            ->method('getFactory')
            ->willReturn($this->cmsBlockCollectorBusinessFactoryMock);
    }

    /**
     * @uses CollectorFacadeInterface::runCollector()
     *
     * @return void
     */
    public function testStorageCmsBlockCollectorCallsRunCollector()
    {
        $this->collectorFacadeMock
            ->expects($this->exactly(1))
            ->method('runCollector');

        $this->cmsBlockCollectorFacadeMock->runStorageCmsBlockCollector(
            $this->getMockBuilder(SpyTouchQuery::class)->disableOriginalConstructor()->getMock(),
            new LocaleTransfer(),
            $this->getMockBuilder(BatchResultInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(ReaderInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(WriterInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(TouchUpdaterInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(OutputInterface::class)->disableOriginalConstructor()->getMock()
        );
    }
}
