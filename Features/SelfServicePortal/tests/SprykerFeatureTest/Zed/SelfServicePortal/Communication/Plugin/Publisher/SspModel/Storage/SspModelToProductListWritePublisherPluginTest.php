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
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspModel\Storage\SspModelToProductListWritePublisherPlugin;
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
 * @group SspModelToProductListWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class SspModelToProductListWritePublisherPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SSP_MODEL_NAME_1 = 'Test Model 1';

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
    protected const STORAGE_FIELD_ID_MODEL = 'id_model';

    /**
     * @var string
     */
    protected const STORAGE_FIELD_WHITELIST_IDS = 'whitelist_ids';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspModel\SspModelToProductListWritePublisherPlugin
     */
    protected SspModelToProductListWritePublisherPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new SspModelToProductListWritePublisherPlugin();
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->clearSspModelStorageData();
        $this->tester->clearSspModelData();
    }

    public function testHandlesBulkPublishesStorageDataOnCreateEvent(): void
    {
        // Arrange
        $productListTransfer = $this->tester->haveProductList([
            ProductListTransfer::TITLE => static::PRODUCT_LIST_TITLE_1,
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_1,
        ]);

        $attachmentEntity = $this->tester->haveSspModelToProductListAttachment(
            $sspModelTransfer->getIdSspModelOrFail(),
            $productListTransfer->getIdProductListOrFail(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($attachmentEntity->getIdSspModelToProductList())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_CREATE)
                ->setForeignKeys([
                    'spy_ssp_model_to_product_list.fk_ssp_model' => $sspModelTransfer->getIdSspModelOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_CREATE);

        // Assert
        $storageData = $this->tester->findSspModelStorageData($sspModelTransfer->getIdSspModel());
        $this->assertNotNull($storageData);
        $this->assertSame($sspModelTransfer->getIdSspModel(), $storageData[static::STORAGE_FIELD_ID_MODEL]);
        $this->assertSame([$productListTransfer->getIdProductList()], $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
    }

    public function testHandlesBulkPublishesStorageDataOnUpdateEvent(): void
    {
        // Arrange
        $productListTransfer = $this->tester->haveProductList([
            ProductListTransfer::TITLE => static::PRODUCT_LIST_TITLE_1,
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_1,
        ]);

        $attachmentEntity = $this->tester->haveSspModelToProductListAttachment(
            $sspModelTransfer->getIdSspModelOrFail(),
            $productListTransfer->getIdProductListOrFail(),
        );

        $this->tester->haveSspModelStorage($sspModelTransfer->getIdSspModelOrFail(), []);

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($attachmentEntity->getIdSspModelToProductList())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_UPDATE)
                ->setForeignKeys([
                    'spy_ssp_model_to_product_list.fk_ssp_model' => $sspModelTransfer->getIdSspModelOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_UPDATE);

        // Assert
        $storageData = $this->tester->findSspModelStorageData($sspModelTransfer->getIdSspModel());
        $this->assertNotNull($storageData);
        $this->assertSame($sspModelTransfer->getIdSspModel(), $storageData[static::STORAGE_FIELD_ID_MODEL]);
        $this->assertSame([$productListTransfer->getIdProductList()], $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
    }

    public function testHandlesBulkPublishesStorageDataOnDeleteEvent(): void
    {
        // Arrange
        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_1,
        ]);

        $this->tester->haveSspModelStorage($sspModelTransfer->getIdSspModelOrFail(), []);
        $this->tester->clearSspModelData();

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId(1)
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_DELETE)
                ->setForeignKeys([
                    'spy_ssp_model_to_product_list.fk_ssp_model' => $sspModelTransfer->getIdSspModelOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_DELETE);

        // Assert
        $storageData = $this->tester->findSspModelStorageData($sspModelTransfer->getIdSspModel());
        $this->assertNull($storageData);
    }

    public function testHandlesBulkWithMultipleProductListAttachments(): void
    {
        // Arrange
        $productListTransfer1 = $this->tester->haveProductList([
            ProductListTransfer::TITLE => static::PRODUCT_LIST_TITLE_1,
        ]);

        $productListTransfer2 = $this->tester->haveProductList([
            ProductListTransfer::TITLE => static::PRODUCT_LIST_TITLE_2,
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_1,
        ]);

        $attachmentEntity1 = $this->tester->haveSspModelToProductListAttachment(
            $sspModelTransfer->getIdSspModelOrFail(),
            $productListTransfer1->getIdProductListOrFail(),
        );

        $attachmentEntity2 = $this->tester->haveSspModelToProductListAttachment(
            $sspModelTransfer->getIdSspModelOrFail(),
            $productListTransfer2->getIdProductListOrFail(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($attachmentEntity1->getIdSspModelToProductList())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_CREATE)
                ->setForeignKeys([
                    'spy_ssp_model_to_product_list.fk_ssp_model' => $sspModelTransfer->getIdSspModelOrFail(),
                ]),
            (new EventEntityTransfer())
                ->setId($attachmentEntity2->getIdSspModelToProductList())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_CREATE)
                ->setForeignKeys([
                    'spy_ssp_model_to_product_list.fk_ssp_model' => $sspModelTransfer->getIdSspModelOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_CREATE);

        // Assert
        $storageData = $this->tester->findSspModelStorageData($sspModelTransfer->getIdSspModel());
        $this->assertNotNull($storageData);
        $this->assertSame($sspModelTransfer->getIdSspModel(), $storageData[static::STORAGE_FIELD_ID_MODEL]);
        $this->assertCount(2, $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
        $this->assertContains($productListTransfer1->getIdProductList(), $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
        $this->assertContains($productListTransfer2->getIdProductList(), $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
    }

    public function testHandlesBulkWithEmptyEvents(): void
    {
        // Arrange
        // Act
        $this->plugin->handleBulk([], SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_CREATE);

        // Assert
        $this->assertTrue(true);
    }

    public function testHandlesBulkUpdatesExistingStorageData(): void
    {
        // Arrange
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

        $attachmentEntity1 = $this->tester->haveSspModelToProductListAttachment(
            $sspModelTransfer->getIdSspModelOrFail(),
            $productListTransfer1->getIdProductListOrFail(),
        );

        $attachmentEntity2 = $this->tester->haveSspModelToProductListAttachment(
            $sspModelTransfer->getIdSspModelOrFail(),
            $productListTransfer2->getIdProductListOrFail(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($attachmentEntity1->getIdSspModelToProductList())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_UPDATE)
                ->setForeignKeys([
                    'spy_ssp_model_to_product_list.fk_ssp_model' => $sspModelTransfer->getIdSspModelOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_UPDATE);

        // Assert
        $storageData = $this->tester->findSspModelStorageData($sspModelTransfer->getIdSspModel());
        $this->assertNotNull($storageData);
        $this->assertSame($sspModelTransfer->getIdSspModel(), $storageData[static::STORAGE_FIELD_ID_MODEL]);
        $this->assertCount(2, $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
        $this->assertContains($productListTransfer1->getIdProductList(), $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
        $this->assertContains($productListTransfer2->getIdProductList(), $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
        $this->assertNotContains(999, $storageData[static::STORAGE_FIELD_WHITELIST_IDS]);
    }

    public function testHandlesBulkDeletesStorageForNonExistentModel(): void
    {
        // Arrange
        $nonExistentModelId = 99999;
        $this->tester->haveSspModelStorage($nonExistentModelId, [1, 2, 3]);

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId(1)
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_DELETE)
                ->setForeignKeys([
                    'spy_ssp_model_to_product_list.fk_ssp_model' => $nonExistentModelId,
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_DELETE);

        // Assert
        $storageData = $this->tester->findSspModelStorageData($nonExistentModelId);
        $this->assertNull($storageData);
    }

    public function testGetSubscribedEventsReturnsCorrectEvents(): void
    {
        // Arrange
        // Act
        $subscribedEvents = $this->plugin->getSubscribedEvents();

        // Assert
        $expectedEvents = [
            SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_CREATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_UPDATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_MODEL_TO_PRODUCT_LIST_DELETE,
        ];

        $this->assertSame($expectedEvents, $subscribedEvents);
    }
}
