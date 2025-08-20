<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspModel\Storage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspModel\Storage\SspModelWritePublisherPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group SspModel
 * @group SspModelWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class SspModelWritePublisherPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SSP_MODEL_NAME_1 = 'Test Model 1';

    /**
     * @var string
     */
    protected const SSP_MODEL_NAME_2 = 'Test Model 2';

    /**
     * @var string
     */
    protected const PRODUCT_LIST_TITLE_1 = 'Test Product List 1';

    /**
     * @var string
     */
    protected const PRODUCT_LIST_TITLE_2 = 'Test Product List 2';

    /**
     * @var string
     */
    protected const PRODUCT_LIST_TITLE_3 = 'Test Product List 3';

    /**
     * @var string
     */
    protected const STORAGE_FIELD_ID_MODEL = 'id_model';

    /**
     * @var string
     */
    protected const STORAGE_FIELD_WHITELIST_IDS = 'whitelist_ids';

    /**
     * @var string
     */
    protected const DEFAULT_LOCALE = 'DE';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspModel\SspModelWritePublisherPlugin
     */
    protected SspModelWritePublisherPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new SspModelWritePublisherPlugin();
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->clearSspModelData();
        $this->tester->clearSspModelStorageData();
    }

    public function testHandlesBulkWithEmptyEvents(): void
    {
        $this->plugin->handleBulk([], SelfServicePortalConfig::SSP_MODEL_PUBLISH);

        $this->assertTrue(true);
    }

    public function testHandlesBulkPublishesStorageDataCorrectly(): void
    {
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_LOCALE]);

        $productListTransfer1 = $this->tester->haveProductList([
            ProductListTransfer::TITLE => static::PRODUCT_LIST_TITLE_1,
        ]);
        $productListTransfer2 = $this->tester->haveProductList([
            ProductListTransfer::TITLE => static::PRODUCT_LIST_TITLE_2,
        ]);

        $sspModelTransfer1 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_1,
        ]);
        $sspModelTransfer2 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_2,
        ]);

        $this->tester->haveSspModelToProductListAssignment(
            $sspModelTransfer1->getIdSspModelOrFail(),
            $productListTransfer1->getIdProductListOrFail(),
        );
        $this->tester->haveSspModelToProductListAssignment(
            $sspModelTransfer1->getIdSspModelOrFail(),
            $productListTransfer2->getIdProductListOrFail(),
        );
        $this->tester->haveSspModelToProductListAssignment(
            $sspModelTransfer2->getIdSspModelOrFail(),
            $productListTransfer1->getIdProductListOrFail(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($sspModelTransfer1->getIdSspModelOrFail()),
            (new EventEntityTransfer())->setId($sspModelTransfer2->getIdSspModelOrFail()),
        ];

        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::SSP_MODEL_PUBLISH);

        $storageData1 = $this->tester->findSspModelStorageData($sspModelTransfer1->getIdSspModel());
        $this->assertNotNull($storageData1);
        $this->assertSame($sspModelTransfer1->getIdSspModel(), $storageData1[static::STORAGE_FIELD_ID_MODEL]);
        $this->assertSame([$productListTransfer1->getIdProductList(), $productListTransfer2->getIdProductList()], $storageData1[static::STORAGE_FIELD_WHITELIST_IDS]);

        $storageData2 = $this->tester->findSspModelStorageData($sspModelTransfer2->getIdSspModel());
        $this->assertNotNull($storageData2);
        $this->assertSame($sspModelTransfer2->getIdSspModel(), $storageData2[static::STORAGE_FIELD_ID_MODEL]);
        $this->assertSame([$productListTransfer1->getIdProductList()], $storageData2[static::STORAGE_FIELD_WHITELIST_IDS]);
    }

    public function testHandlesBulkPublishesStorageDataCorrectlyWithMultipleProductListAssignments(): void
    {
        $productListTransfer1 = $this->tester->haveProductList([
            ProductListTransfer::TITLE => static::PRODUCT_LIST_TITLE_1,
        ]);
        $productListTransfer2 = $this->tester->haveProductList([
            ProductListTransfer::TITLE => static::PRODUCT_LIST_TITLE_2,
        ]);
        $productListTransfer3 = $this->tester->haveProductList([
            ProductListTransfer::TITLE => static::PRODUCT_LIST_TITLE_3,
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_1,
        ]);

        $this->tester->haveSspModelToProductListAssignment(
            $sspModelTransfer->getIdSspModelOrFail(),
            $productListTransfer1->getIdProductListOrFail(),
        );
        $this->tester->haveSspModelToProductListAssignment(
            $sspModelTransfer->getIdSspModelOrFail(),
            $productListTransfer2->getIdProductListOrFail(),
        );
        $this->tester->haveSspModelToProductListAssignment(
            $sspModelTransfer->getIdSspModelOrFail(),
            $productListTransfer3->getIdProductListOrFail(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($sspModelTransfer->getIdSspModelOrFail()),
        ];

        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::SSP_MODEL_PUBLISH);

        $storageData = $this->tester->findSspModelStorageData($sspModelTransfer->getIdSspModel());
        $this->assertNotNull($storageData);
        $this->assertSame($sspModelTransfer->getIdSspModel(), $storageData[static::STORAGE_FIELD_ID_MODEL]);
        $this->assertSame([
            $productListTransfer1->getIdProductList(),
            $productListTransfer2->getIdProductList(),
            $productListTransfer3->getIdProductList(),
        ], $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
    }

    public function testHandlesBulkUpdatesExistingStorageData(): void
    {
        $productListTransfer1 = $this->tester->haveProductList([
            ProductListTransfer::TITLE => static::PRODUCT_LIST_TITLE_1,
        ]);
        $productListTransfer2 = $this->tester->haveProductList([
            ProductListTransfer::TITLE => static::PRODUCT_LIST_TITLE_2,
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_1,
        ]);

        $this->tester->haveSspModelStorage($sspModelTransfer->getIdSspModelOrFail(), [999]);

        $this->tester->haveSspModelToProductListAssignment(
            $sspModelTransfer->getIdSspModelOrFail(),
            $productListTransfer1->getIdProductListOrFail(),
        );
        $this->tester->haveSspModelToProductListAssignment(
            $sspModelTransfer->getIdSspModelOrFail(),
            $productListTransfer2->getIdProductListOrFail(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($sspModelTransfer->getIdSspModelOrFail()),
        ];

        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::SSP_MODEL_PUBLISH);

        $storageData = $this->tester->findSspModelStorageData($sspModelTransfer->getIdSspModel());
        $this->assertNotNull($storageData);
        $this->assertSame($sspModelTransfer->getIdSspModel(), $storageData[static::STORAGE_FIELD_ID_MODEL]);
        $this->assertCount(2, $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
        $this->assertContains($productListTransfer1->getIdProductListOrFail(), $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
        $this->assertContains($productListTransfer2->getIdProductListOrFail(), $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
        $this->assertNotContains(999, $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
    }

    public function testHandlesBulkDeletesStorageForNonExistentModel(): void
    {
        $nonExistentModelId = 99999;
        $this->tester->haveSspModelStorageEntity([
            static::STORAGE_FIELD_ID_MODEL => $nonExistentModelId,
            static::STORAGE_FIELD_WHITELIST_IDS => [1, 2, 3],
        ]);

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($nonExistentModelId),
        ];

        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::SSP_MODEL_PUBLISH);

        $storageData = $this->tester->findSspModelStorageData($nonExistentModelId);
        $this->assertNull($storageData);
    }
}
