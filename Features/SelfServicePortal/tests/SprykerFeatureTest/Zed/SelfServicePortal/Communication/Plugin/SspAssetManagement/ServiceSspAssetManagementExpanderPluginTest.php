<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Sales;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Generated\Shared\Transfer\SspServiceTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ServiceReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\SspAssetManagement\ServiceSspAssetManagementExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group SspAssetManagement
 * @group ServiceSspAssetManagementExpanderPluginTest
 */
class ServiceSspAssetManagementExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const ASSET_REFERENCE_1 = 'SSP-ASSET-1';

    /**
     * @var string
     */
    protected const ASSET_REFERENCE_2 = 'SSP-ASSET-2';

    /**
     * @var string
     */
    protected const ASSET_REFERENCE_3 = 'SSP-ASSET-3';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandShouldReturnCorrectSspAssetCollection(): void
    {
        // Arrange
        $sspAssetTransfer1 = (new SspAssetTransfer())->setReference(static::ASSET_REFERENCE_1);
        $sspAssetTransfer2 = (new SspAssetTransfer())->setReference(static::ASSET_REFERENCE_2);

        $sspAssetCollectionTransfer = (new SspAssetCollectionTransfer())
            ->setSspAssets(new ArrayObject([$sspAssetTransfer1, $sspAssetTransfer2]));

        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())
            ->setInclude((new SspAssetIncludeTransfer())->setWithServicesCount(4))
            ->setCompanyUser((new CompanyUserTransfer()));

        $sspServiceCollectionTransfer = $this->createMockSspServiceCollection();

        $factoryMock = $this->createFactoryMock($sspServiceCollectionTransfer);

        $plugin = new ServiceSspAssetManagementExpanderPlugin();
        $plugin->setBusinessFactory($factoryMock);

        // Act
        $resultSspAssetCollectionTransfer = $plugin->expand($sspAssetCollectionTransfer, $sspAssetCriteriaTransfer);

        // Assert
        $this->assertCount(2, $resultSspAssetCollectionTransfer->getSspAssets());

        foreach ($resultSspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $this->assertNotNull($sspAssetTransfer->getSspServiceCollection());
            $this->assertCount(1, $sspAssetTransfer->getSspServiceCollectionOrFail()->getServices());
        }
    }

    /**
     * @return \Generated\Shared\Transfer\SspServiceCollectionTransfer
     */
    protected function createMockSspServiceCollection(): SspServiceCollectionTransfer
    {
        $sspServiceTransfer = (new SspServiceTransfer())
            ->setSspAssets(new ArrayObject([
                (new SspAssetTransfer())->setReference(static::ASSET_REFERENCE_1),
                (new SspAssetTransfer())->setReference(static::ASSET_REFERENCE_2),
            ]));

        return (new SspServiceCollectionTransfer())
            ->setServices(new ArrayObject([$sspServiceTransfer]))
            ->setPagination((new PaginationTransfer())->setPage(1)->setMaxPerPage(4));
    }

    /**
     * @param \Generated\Shared\Transfer\SspServiceCollectionTransfer $sspServiceCollectionTransfer
     *
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacade
     */
    protected function createFactoryMock(SspServiceCollectionTransfer $sspServiceCollectionTransfer): SelfServicePortalBusinessFactory
    {
        $factoryMock = $this->createPartialMock(SelfServicePortalBusinessFactory::class, ['createServiceReader']);

        $serviceReaderMock = $this->getMockBuilder(ServiceReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $serviceReaderMock
            ->method('getServiceCollection')
            ->with($this->isInstanceOf(SspServiceCriteriaTransfer::class))
            ->willReturn($sspServiceCollectionTransfer);

        $factoryMock
            ->expects($this->once())
            ->method('createServiceReader')
            ->willReturn($serviceReaderMock);

        return $factoryMock;
    }
}
