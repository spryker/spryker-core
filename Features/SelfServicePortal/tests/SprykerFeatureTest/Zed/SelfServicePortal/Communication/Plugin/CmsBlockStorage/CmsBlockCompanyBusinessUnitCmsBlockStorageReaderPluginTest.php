<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\CmsBlockStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsBlockRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use SprykerFeature\Client\SelfServicePortal\Dashboard\Reader\CmsBlockCompanyBusinessUnitStorageReader;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalFactory;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Communication
 * @group Plugin
 * @group CmsBlockStorage
 * @group CmsBlockCompanyBusinessUnitCmsBlockStorageReaderPluginTest
 *
 * Add your own group annotations below this line
 */
class CmsBlockCompanyBusinessUnitCmsBlockStorageReaderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCmsBlockCompanyBusinessUnitCmsBlockStorageReaderPluginResolvesCorrectBlock(): void
    {
        // Arrange
        $mockStorageClient = $this->getMockBuilder(StorageClient::class)->onlyMethods(['get'])->getMock();
        $mockStorageClient->method('get')->willReturn([
            'id' => 'block-999',
        ]);

        $mockStoreClient = $this->getMockBuilder(StoreClientInterface::class)->getMock();
        $mockStoreClient->method('getCurrentStore')->willReturn((new StoreTransfer())->setName('DE'));
        $mockLocaleClient = $this->getMockBuilder(LocaleClientInterface::class)->getMock();
        $mockLocaleClient->method('getCurrentLocale')->willReturn('de_DE');
        $mockSynchronizationService = $this->getMockBuilder(SynchronizationServiceInterface::class)->getMock();
        $mockSynchronizationKeyGeneratorPlugin = $this->getMockBuilder(SynchronizationKeyGeneratorPluginInterface::class)->getMock();
        $mockSynchronizationKeyGeneratorPlugin->method('generateKey')->willReturn('key');
        $mockSynchronizationService->method('getStorageKeyBuilder')->willReturn($mockSynchronizationKeyGeneratorPlugin);

        $mockSspDashboardManagementFactory = $this->getMockBuilder(SelfServicePortalFactory::class)->getMock();
        $mockSspDashboardManagementFactory->method('getStorageClient')->willReturn($mockStorageClient);
        $mockSspDashboardManagementFactory->method('getStoreClient')->willReturn($mockStoreClient);
        $mockSspDashboardManagementFactory->method('getLocaleClient')->willReturn($mockLocaleClient);
        $mockSspDashboardManagementFactory->method('getSynchronizationService')->willReturn($mockSynchronizationService);

        $cmsBlockCompanyBusinessUnitStorageReader = new CmsBlockCompanyBusinessUnitStorageReader(
            $mockStorageClient,
            $mockSynchronizationService,
            $mockStoreClient,
            $mockLocaleClient,
        );

        // Act
        $cmsBlockTransfers = $cmsBlockCompanyBusinessUnitStorageReader->getCmsBlocks(
            (new CmsBlockRequestTransfer())
                ->setCompanyUnit(999)
                ->setCompanyUnitBlockName('test_sales_rep'),
        );

        // Assert
        $this->assertIsArray($cmsBlockTransfers);
        $this->assertSame('block-999', $cmsBlockTransfers[0]?->getKey());
    }
}
