<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Synchronization\Search;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Store\StoreDependencyProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Synchronization\SspAsset\Search\SspAssetListSynchronizationDataBulkRepositoryPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Synchronization
 * @group SspAssetSynchronizationDataRepositoryPluginTest
 */
class SspAssetSynchronizationDataRepositoryPluginTest extends Unit
{
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @var string
     */
    protected const STORE_NAME_UF = 'UF';

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(
            QueueDependencyProvider::QUEUE_ADAPTERS,
            function (Container $container) {
                return [
                    $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
                ];
            },
        );

        $this->tester->setDependency(
            StoreDependencyProvider::SERVICE_STORE,
            static::STORE_NAME_UF,
        );
    }

    public function testGetDataReturnsSynchronizationDataTransfers(): void
    {
        // Arrange
        $assetOne = $this->tester->haveAsset();
        $assetTwo = $this->tester->haveAsset();

        $sspAssetSearchEntity1 = $this->tester->haveSspAssetSearch([
            'fk_ssp_asset' => $assetOne->getIdSspAsset(),
            'data' => '{"type":"ssp_asset","name":"A1","store":"UF"}',
            'structured_data' => '{"type":"ssp_asset","name":"A1","store":"UF"}',
            'key' => 'key:1',
        ]);

        $sspAssetSearchEntity2 = $this->tester->haveSspAssetSearch([
            'fk_ssp_asset' => $assetTwo->getIdSspAsset(),
            'data' => '{"type":"ssp_asset","name":"A2","store":"LO"}',
            'structured_data' => '{"type":"ssp_asset","name":"A2","store":"LO"}',
            'key' => 'key:2',
        ]);

        // Act
        $sspAssetSynchronizationDataRepositoryPlugin = new SspAssetListSynchronizationDataBulkRepositoryPlugin();
        $synchronizationDataTransfers = $sspAssetSynchronizationDataRepositoryPlugin->getData(0, 10, [$sspAssetSearchEntity1->getFkSspAsset(), $sspAssetSearchEntity2->getFkSspAsset()]);

        // Assert
        $this->assertCount(2, $synchronizationDataTransfers);

        $this->assertSame($sspAssetSearchEntity1->getKey(), $synchronizationDataTransfers[0]->getKey());
        $this->assertSame($sspAssetSearchEntity2->getKey(), $synchronizationDataTransfers[1]->getKey());

        $this->assertSame($sspAssetSearchEntity1->getData(), $synchronizationDataTransfers[0]->getData());
        $this->assertSame($sspAssetSearchEntity2->getData(), $synchronizationDataTransfers[1]->getData());
    }
}
