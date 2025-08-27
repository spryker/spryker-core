<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\Cms\Business\MessageBroker;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CmsPageBuilder;
use Generated\Shared\DataBuilder\CmsVersionBuilder;
use Generated\Shared\Transfer\CmsPageMessageBrokerRequestTransfer;
use Generated\Shared\Transfer\CmsPagePublishedTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsPageUnpublishedTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\Cms\Business\Extractor\DataExtractorInterface;
use Spryker\Zed\Cms\Business\MessageBroker\CmsPageMessageBrokerPublisher;
use Spryker\Zed\Cms\Business\Page\CmsPageReaderInterface;
use Spryker\Zed\Cms\Business\Page\LocaleCmsPageDataExpanderInterface;
use Spryker\Zed\Cms\Business\Version\VersionFinderInterface;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\Dependency\Facade\CmsToMessageBrokerFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group MessageBroker
 * @group CmsPageMessageBrokerPublisherTest
 * Add your own group annotations below this line
 */
class CmsPageMessageBrokerPublisherTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Cms\CmsBusinessTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const TEST_TENANT_IDENTIFIER = 'test-tenant';

    /**
     * @var int
     */
    protected const TEST_CMS_PAGE_ID = 123;

    /**
     * @var int
     */
    protected const TEST_CMS_VERSION_ID = 456;

    /**
     * @var int
     */
    protected const TEST_FK_PAGE = 789;

    /**
     * @return void
     */
    public function testSendCmsPagesToMessageBrokerWithVersionIds(): void
    {
        // Arrange
        $cmsVersionIds = [static::TEST_CMS_VERSION_ID];
        $cmsPageMessageBrokerRequestTransfer = (new CmsPageMessageBrokerRequestTransfer())
            ->setCmsVersionIds($cmsVersionIds);

        $cmsVersionTransfer = $this->createCmsVersionTransfer();
        $cmsVersionTransfers = new ArrayObject([$cmsVersionTransfer]);
        $cmsPageTransfer = $this->createCmsPageTransfer();

        $mocks = $this->createPublisherDependenciesMock($cmsPageTransfer);
        $cmsConfig = $this->createConfigWithTenantIdentifier();

        $mocks['versionFinder']
            ->expects($this->once())
            ->method('findCmsVersionsByIds')
            ->with($cmsVersionIds)
            ->willReturn($cmsVersionTransfers);

        $mocks['messageBrokerFacade']
            ->expects($this->once())
            ->method('sendMessage')
            ->with($this->callback(function ($transfer) {
                return $transfer instanceof CmsPagePublishedTransfer
                    && $transfer->getId() === static::TEST_FK_PAGE
                    && $transfer->getCmsPage() !== null
                    && $transfer->getMessageAttributes()->getTenantIdentifier() === static::TEST_TENANT_IDENTIFIER;
            }));

        $publisher = $this->createPublisher($mocks, $cmsConfig);

        // Act
        $publisher->sendCmsPagesToMessageBroker($cmsPageMessageBrokerRequestTransfer);
    }

    /**
     * @dataProvider pageStatusDataProvider
     *
     * @param bool $isActive
     * @param bool $isSearchable
     * @param string $expectedMessageType
     * @param bool $shouldCallVersionFinder
     *
     * @return void
     */
    public function testSendCmsPagesToMessageBrokerWithDifferentPageStatus(
        bool $isActive,
        bool $isSearchable,
        string $expectedMessageType,
        bool $shouldCallVersionFinder
    ): void {
        // Arrange
        $cmsPageIds = [static::TEST_CMS_PAGE_ID];
        $cmsPageMessageBrokerRequestTransfer = (new CmsPageMessageBrokerRequestTransfer())
            ->setCmsPageIds($cmsPageIds);

        $cmsPageTransfer = $this->createCmsPageTransfer($isActive, $isSearchable);
        $cmsVersionTransfer = $this->createCmsVersionTransfer();

        $mocks = $this->createPublisherDependenciesMock($cmsPageTransfer);
        $cmsConfig = $this->createConfigWithTenantIdentifier();

        if ($shouldCallVersionFinder) {
            $mocks['versionFinder']
                ->expects($this->once())
                ->method('findLatestCmsVersionByIdCmsPage')
                ->with(static::TEST_CMS_PAGE_ID)
                ->willReturn($cmsVersionTransfer);
        } else {
            $mocks['versionFinder']
                ->expects($this->never())
                ->method('findLatestCmsVersionByIdCmsPage');
        }

        $mocks['messageBrokerFacade']
            ->expects($this->once())
            ->method('sendMessage')
            ->with($this->callback(function ($transfer) use ($expectedMessageType) {
                if ($transfer instanceof CmsPagePublishedTransfer) {
                    return $transfer->getId() === static::TEST_FK_PAGE
                        && $transfer->getCmsPage() !== null
                        && $transfer->getMessageAttributes()->getTenantIdentifier() === static::TEST_TENANT_IDENTIFIER;
                }

                if ($transfer instanceof CmsPageUnpublishedTransfer) {
                    return $transfer->getId() === static::TEST_CMS_PAGE_ID
                        && $transfer->getMessageAttributes()->getTenantIdentifier() === static::TEST_TENANT_IDENTIFIER;
                }

                return false;
            }));

        $publisher = $this->createPublisher($mocks, $cmsConfig);

        // Act
        $publisher->sendCmsPagesToMessageBroker($cmsPageMessageBrokerRequestTransfer);
    }

    /**
     * @return void
     */
    public function testSendCmsPagesToMessageBrokerHandlesEmptyVersions(): void
    {
        // Arrange
        $cmsVersionIds = [static::TEST_CMS_VERSION_ID];
        $cmsPageMessageBrokerRequestTransfer = (new CmsPageMessageBrokerRequestTransfer())
            ->setCmsVersionIds($cmsVersionIds);

        $emptyVersionTransfers = new ArrayObject();

        $mocks = $this->createPublisherDependenciesMock();
        $cmsConfig = $this->createConfigWithTenantIdentifier();

        $mocks['versionFinder']
            ->expects($this->once())
            ->method('findCmsVersionsByIds')
            ->with($cmsVersionIds)
            ->willReturn($emptyVersionTransfers);

        $mocks['messageBrokerFacade']
            ->expects($this->never())
            ->method('sendMessage');

        $publisher = $this->createPublisher($mocks, $cmsConfig);

        // Act
        $publisher->sendCmsPagesToMessageBroker($cmsPageMessageBrokerRequestTransfer);
    }

    /**
     * @return void
     */
    public function testSendCmsPagesToMessageBrokerHandlesNonExistentPage(): void
    {
        // Arrange
        $cmsPageIds = [static::TEST_CMS_PAGE_ID];
        $cmsPageMessageBrokerRequestTransfer = (new CmsPageMessageBrokerRequestTransfer())
            ->setCmsPageIds($cmsPageIds);

        $mocks = $this->createPublisherDependenciesMock(null);
        $cmsConfig = $this->createConfigWithTenantIdentifier();

        $mocks['messageBrokerFacade']
            ->expects($this->never())
            ->method('sendMessage');

        $publisher = $this->createPublisher($mocks, $cmsConfig);

        // Act
        $publisher->sendCmsPagesToMessageBroker($cmsPageMessageBrokerRequestTransfer);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public function pageStatusDataProvider(): array
    {
        return [
            'active and searchable page sends published message' => [
                true, // isActive
                true, // isSearchable
                CmsPagePublishedTransfer::class, // expectedMessageType
                true, // shouldCallVersionFinder
            ],
            'inactive page sends unpublished message' => [
                false, // isActive
                false, // isSearchable
                CmsPageUnpublishedTransfer::class, // expectedMessageType
                false, // shouldCallVersionFinder
            ],
            'active but not searchable page sends unpublished message' => [
                true, // isActive
                false, // isSearchable
                CmsPageUnpublishedTransfer::class, // expectedMessageType
                false, // shouldCallVersionFinder
            ],
        ];
    }

    /**
     * @param bool $isActive
     * @param bool $isSearchable
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function createCmsPageTransfer(bool $isActive = true, bool $isSearchable = true): CmsPageTransfer
    {
        return (new CmsPageBuilder([
            'idCmsPage' => static::TEST_CMS_PAGE_ID,
            'fkPage' => static::TEST_FK_PAGE,
            'isActive' => $isActive,
            'isSearchable' => $isSearchable,
        ]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    protected function createCmsVersionTransfer(): CmsVersionTransfer
    {
        return (new CmsVersionBuilder([
            'idCmsVersion' => static::TEST_CMS_VERSION_ID,
            'fkCmsPage' => static::TEST_CMS_PAGE_ID,
            'createdAt' => '2023-01-01 12:00:00',
        ]))->build();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer|null $cmsPageTransfer
     *
     * @return array<string, mixed>
     */
    protected function createPublisherDependenciesMock(?CmsPageTransfer $cmsPageTransfer = null): array
    {
        $cmsPageReaderMock = $this->getMockBuilder(CmsPageReaderInterface::class)->getMock();

        if ($cmsPageTransfer !== null) {
            $cmsPageReaderMock
                ->expects($this->once())
                ->method('findCmsPageById')
                ->with(static::TEST_CMS_PAGE_ID)
                ->willReturn($cmsPageTransfer);
        }

        return [
            'messageBrokerFacade' => $this->getMockBuilder(CmsToMessageBrokerFacadeInterface::class)->getMock(),
            'cmsPageReader' => $cmsPageReaderMock,
            'cmsRepository' => $this->getMockBuilder(CmsRepositoryInterface::class)->getMock(),
            'versionFinder' => $this->getMockBuilder(VersionFinderInterface::class)->getMock(),
            'dataExtractor' => $this->getMockBuilder(DataExtractorInterface::class)->getMock(),
            'localeCmsPageDataExpander' => $this->getMockBuilder(LocaleCmsPageDataExpanderInterface::class)->getMock(),
        ];
    }

    /**
     * @return \Spryker\Zed\Cms\CmsConfig
     */
    protected function createConfigWithTenantIdentifier(): CmsConfig
    {
        $this->tester->mockConfigMethod('getTenantIdentifier', static::TEST_TENANT_IDENTIFIER);

        return $this->tester->getModuleConfig();
    }

    /**
     * @param array<string, mixed> $mocks
     * @param \Spryker\Zed\Cms\CmsConfig $cmsConfig
     *
     * @return \Spryker\Zed\Cms\Business\MessageBroker\CmsPageMessageBrokerPublisher
     */
    protected function createPublisher(array $mocks, CmsConfig $cmsConfig): CmsPageMessageBrokerPublisher
    {
        return new CmsPageMessageBrokerPublisher(
            $mocks['messageBrokerFacade'],
            $mocks['cmsPageReader'],
            $mocks['cmsRepository'],
            $mocks['versionFinder'],
            $mocks['dataExtractor'],
            $mocks['localeCmsPageDataExpander'],
            $cmsConfig,
        );
    }
}
