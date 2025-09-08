<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\Cms\Business\Exporter;

use Codeception\Test\Unit;
use Spryker\Zed\Cms\Business\Exporter\CmsPageEventExporter;
use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\Cms\Dependency\Facade\CmsToEventFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group Exporter
 * @group CmsPageEventExporterTest
 * Add your own group annotations below this line
 */
class CmsPageEventExporterTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Cms\CmsBusinessTester
     */
    protected $tester;

    /**
     * @var array<int>
     */
    protected const TEST_CMS_PAGE_IDS = [1, 2, 3, 4, 5];

    /**
     * @return void
     */
    public function testExportTriggersEventsForActiveCmsPages(): void
    {
        // Arrange
        $eventFacadeMock = $this->createEventFacadeMock();
        $repositoryMock = $this->createRepositoryMock();
        $cmsConfig = $this->tester->getModuleConfig();

        $repositoryMock
            ->expects($this->once())
            ->method('getActiveSearchablePageIds')
            ->willReturn(static::TEST_CMS_PAGE_IDS);

        $eventFacadeMock
            ->expects($this->once())
            ->method('triggerBulk')
            ->with(
                CmsEvents::ENTITY_SPY_CMS_PAGE_EXPORT,
                $this->callback(function (array $eventTransfers) {
                    $this->assertCount(5, $eventTransfers);
                    foreach ($eventTransfers as $eventTransfer) {
                        $this->assertContains($eventTransfer->getId(), static::TEST_CMS_PAGE_IDS);
                    }

                    return true;
                }),
            );

        $exporter = new CmsPageEventExporter($eventFacadeMock, $repositoryMock, $cmsConfig);

        // Act
        $exporter->export();
    }

    /**
     * @dataProvider chunkingDataProvider
     *
     * @param array<int> $pageIds
     * @param int $chunkSize
     * @param int $expectedCalls
     *
     * @return void
     */
    public function testExportHandlesChunking(
        array $pageIds,
        int $chunkSize,
        int $expectedCalls
    ): void {
        // Arrange
        $eventFacadeMock = $this->createEventFacadeMock();
        $repositoryMock = $this->createRepositoryMock();
        $this->tester->mockConfigMethod('getCmsPageExportChunkSize', $chunkSize);
        $cmsConfig = $this->tester->getModuleConfig();

        $repositoryMock
            ->expects($this->once())
            ->method('getActiveSearchablePageIds')
            ->willReturn($pageIds);

        $eventFacadeMock
            ->expects($this->exactly($expectedCalls))
            ->method('triggerBulk')
            ->with(
                CmsEvents::ENTITY_SPY_CMS_PAGE_EXPORT,
                $this->callback(function (array $eventTransfers) use ($chunkSize) {
                    $this->assertLessThanOrEqual($chunkSize, count($eventTransfers));
                    $this->assertGreaterThan(0, count($eventTransfers));

                    return true;
                }),
            );

        $exporter = new CmsPageEventExporter($eventFacadeMock, $repositoryMock, $cmsConfig);

        // Act
        $exporter->export();
    }

    /**
     * Test that verifies CmsPageEventExporter handles empty data correctly
     * and doesn't call unnecessary methods.
     *
     * @return void
     */
    public function testExportHandlesEmptyData(): void
    {
        // Arrange
        $eventFacadeMock = $this->createEventFacadeMock();
        $repositoryMock = $this->createRepositoryMock();
        $cmsConfig = $this->tester->getModuleConfig();

        $repositoryMock
            ->expects($this->once())
            ->method('getActiveSearchablePageIds')
            ->willReturn([]); // No active pages

        $eventFacadeMock
            ->expects($this->never()) // Should not trigger events when no pages
            ->method('triggerBulk');

        $exporter = new CmsPageEventExporter($eventFacadeMock, $repositoryMock, $cmsConfig);

        // Act
        $exporter->export();
    }

    /**
     * @return array<string, array<mixed>>
     */
    public function chunkingDataProvider(): array
    {
        return [
            'dataset with medium chunks' => [
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], // 12 pages
                5, // chunk size
                3, // expected calls (12/5 = 3 chunks: 5, 5, 2)
            ],
            'dataset with small chunks' => [
                [1, 2, 3, 4, 5, 6, 7], // 7 pages
                3, // chunk size
                3, // expected calls (7/3 = 3 chunks: 3, 3, 1)
            ],
            'single item with minimum chunk' => [
                [1], // 1 page
                1, // chunk size
                1, // expected calls
            ],
            'dataset without chunking' => [
                [1, 2, 3, 4, 5], // 5 pages
                10, // chunk size (larger than dataset)
                1, // expected calls (no chunking needed)
            ],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Dependency\Facade\CmsToEventFacadeInterface
     */
    protected function createEventFacadeMock(): CmsToEventFacadeInterface
    {
        return $this->createMock(CmsToEventFacadeInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Persistence\CmsRepositoryInterface
     */
    protected function createRepositoryMock(): CmsRepositoryInterface
    {
        return $this->createMock(CmsRepositoryInterface::class);
    }
}
