<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Business\Version;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsVersionTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsVersion;
use Orm\Zed\Cms\Persistence\SpyCmsVersionQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Cms\Business\Version\Mapper\VersionDataMapperInterface;
use Spryker\Zed\Cms\Business\Version\VersionFinder;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group Version
 * @group VersionFinderTest
 * Add your own group annotations below this line
 */
class VersionFinderTest extends Unit
{
    /**
     * @var int
     */
    protected const TEST_VERSION_ID_1 = 123;

    /**
     * @var int
     */
    protected const TEST_VERSION_ID_2 = 456;

    /**
     * @var int
     */
    protected const TEST_VERSION_ID_3 = 789;

    /**
     * @var array<int>
     */
    protected const TEST_VERSION_IDS = [
        self::TEST_VERSION_ID_1,
        self::TEST_VERSION_ID_2,
        self::TEST_VERSION_ID_3,
    ];

    /**
     * @return void
     */
    public function testFindCmsVersionsByIdsReturnsCorrectVersions(): void
    {
        // Arrange
        $queryContainerMock = $this->createQueryContainerMock();
        $versionDataMapperMock = $this->createVersionDataMapperMock();
        $transferExpanderPlugins = [];

        $cmsVersionEntity1 = $this->createCmsVersionEntityMock(static::TEST_VERSION_ID_1);
        $cmsVersionEntity2 = $this->createCmsVersionEntityMock(static::TEST_VERSION_ID_2);
        $cmsVersionEntity3 = $this->createCmsVersionEntityMock(static::TEST_VERSION_ID_3);

        $cmsVersionCollection = [$cmsVersionEntity1, $cmsVersionEntity2, $cmsVersionEntity3];

        $cmsVersionQueryMock = $this->createCmsVersionQueryMock();
        $cmsVersionQueryMock
            ->expects($this->once())
            ->method('filterByIdCmsVersion')
            ->with(static::TEST_VERSION_IDS, Criteria::IN)
            ->willReturnSelf();

        $cmsVersionQueryMock
            ->expects($this->once())
            ->method('find')
            ->willReturn($cmsVersionCollection);

        $queryContainerMock
            ->expects($this->once())
            ->method('queryAllCmsVersions')
            ->willReturn($cmsVersionQueryMock);

        $versionTransfer1 = $this->createCmsVersionTransfer(static::TEST_VERSION_ID_1);
        $versionTransfer2 = $this->createCmsVersionTransfer(static::TEST_VERSION_ID_2);
        $versionTransfer3 = $this->createCmsVersionTransfer(static::TEST_VERSION_ID_3);

        $versionDataMapperMock
            ->expects($this->exactly(3))
            ->method('mapToCmsVersionTransfer')
            ->willReturnOnConsecutiveCalls($versionTransfer1, $versionTransfer2, $versionTransfer3);

        $versionFinder = new VersionFinder($queryContainerMock, $versionDataMapperMock, $transferExpanderPlugins);

        // Act
        $result = $versionFinder->findCmsVersionsByIds(static::TEST_VERSION_IDS);

        // Assert
        $this->assertCount(3, $result);
        $this->assertSame($versionTransfer1, $result[0]);
        $this->assertSame($versionTransfer2, $result[1]);
        $this->assertSame($versionTransfer3, $result[2]);
    }

    /**
     * @return void
     */
    public function testFindCmsVersionsByIdsWithEmptyArrayReturnsEmptyCollection(): void
    {
        // Arrange
        $queryContainerMock = $this->createQueryContainerMock();
        $versionDataMapperMock = $this->createVersionDataMapperMock();
        $transferExpanderPlugins = [];

        $cmsVersionCollection = [];

        $cmsVersionQueryMock = $this->createCmsVersionQueryMock();
        $cmsVersionQueryMock
            ->expects($this->once())
            ->method('filterByIdCmsVersion')
            ->with([], Criteria::IN)
            ->willReturnSelf();

        $cmsVersionQueryMock
            ->expects($this->once())
            ->method('find')
            ->willReturn($cmsVersionCollection);

        $queryContainerMock
            ->expects($this->once())
            ->method('queryAllCmsVersions')
            ->willReturn($cmsVersionQueryMock);

        $versionDataMapperMock
            ->expects($this->never())
            ->method('mapToCmsVersionTransfer');

        $versionFinder = new VersionFinder($queryContainerMock, $versionDataMapperMock, $transferExpanderPlugins);

        // Act
        $result = $versionFinder->findCmsVersionsByIds([]);

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * @return void
     */
    public function testFindCmsVersionsByIdsWithNonExistentIdsReturnsEmptyCollection(): void
    {
        // Arrange
        $nonExistentIds = [999, 1000, 1001];
        $queryContainerMock = $this->createQueryContainerMock();
        $versionDataMapperMock = $this->createVersionDataMapperMock();
        $transferExpanderPlugins = [];

        $cmsVersionCollection = []; // Empty collection

        $cmsVersionQueryMock = $this->createCmsVersionQueryMock();
        $cmsVersionQueryMock
            ->expects($this->once())
            ->method('filterByIdCmsVersion')
            ->with($nonExistentIds, Criteria::IN)
            ->willReturnSelf();

        $cmsVersionQueryMock
            ->expects($this->once())
            ->method('find')
            ->willReturn($cmsVersionCollection);

        $queryContainerMock
            ->expects($this->once())
            ->method('queryAllCmsVersions')
            ->willReturn($cmsVersionQueryMock);

        $versionDataMapperMock
            ->expects($this->never())
            ->method('mapToCmsVersionTransfer');

        $versionFinder = new VersionFinder($queryContainerMock, $versionDataMapperMock, $transferExpanderPlugins);

        // Act
        $result = $versionFinder->findCmsVersionsByIds($nonExistentIds);

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * @return void
     */
    public function testFindCmsVersionsByIdsWithPartialResultsReturnsOnlyFoundVersions(): void
    {
        // Arrange
        $mixedIds = [static::TEST_VERSION_ID_1, 999, static::TEST_VERSION_ID_2]; // Only first and third exist
        $queryContainerMock = $this->createQueryContainerMock();
        $versionDataMapperMock = $this->createVersionDataMapperMock();
        $transferExpanderPlugins = [];

        $cmsVersionEntity1 = $this->createCmsVersionEntityMock(static::TEST_VERSION_ID_1);
        $cmsVersionEntity2 = $this->createCmsVersionEntityMock(static::TEST_VERSION_ID_2);

        $cmsVersionCollection = [$cmsVersionEntity1, $cmsVersionEntity2];

        $cmsVersionQueryMock = $this->createCmsVersionQueryMock();
        $cmsVersionQueryMock
            ->expects($this->once())
            ->method('filterByIdCmsVersion')
            ->with($mixedIds, Criteria::IN)
            ->willReturnSelf();

        $cmsVersionQueryMock
            ->expects($this->once())
            ->method('find')
            ->willReturn($cmsVersionCollection);

        $queryContainerMock
            ->expects($this->once())
            ->method('queryAllCmsVersions')
            ->willReturn($cmsVersionQueryMock);

        $versionTransfer1 = $this->createCmsVersionTransfer(static::TEST_VERSION_ID_1);
        $versionTransfer2 = $this->createCmsVersionTransfer(static::TEST_VERSION_ID_2);

        $versionDataMapperMock
            ->expects($this->exactly(2))
            ->method('mapToCmsVersionTransfer')
            ->willReturnOnConsecutiveCalls($versionTransfer1, $versionTransfer2);

        $versionFinder = new VersionFinder($queryContainerMock, $versionDataMapperMock, $transferExpanderPlugins);

        // Act
        $result = $versionFinder->findCmsVersionsByIds($mixedIds);

        // Assert
        $this->assertCount(2, $result);
        $this->assertSame($versionTransfer1, $result[0]);
        $this->assertSame($versionTransfer2, $result[1]);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected function createQueryContainerMock(): CmsQueryContainerInterface
    {
        return $this->createMock(CmsQueryContainerInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Business\Version\Mapper\VersionDataMapperInterface
     */
    protected function createVersionDataMapperMock(): VersionDataMapperInterface
    {
        return $this->createMock(VersionDataMapperInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    protected function createCmsVersionQueryMock(): SpyCmsVersionQuery
    {
        return $this->createMock(SpyCmsVersionQuery::class);
    }

    /**
     * @param int $idCmsVersion
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Cms\Persistence\SpyCmsVersion
     */
    protected function createCmsVersionEntityMock(int $idCmsVersion): SpyCmsVersion
    {
        $mock = $this->createMock(SpyCmsVersion::class);
        $mock->method('getIdCmsVersion')->willReturn($idCmsVersion);

        return $mock;
    }

    /**
     * @param int $idCmsVersion
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    protected function createCmsVersionTransfer(int $idCmsVersion): CmsVersionTransfer
    {
        return (new CmsVersionTransfer())->setIdCmsVersion($idCmsVersion);
    }
}
