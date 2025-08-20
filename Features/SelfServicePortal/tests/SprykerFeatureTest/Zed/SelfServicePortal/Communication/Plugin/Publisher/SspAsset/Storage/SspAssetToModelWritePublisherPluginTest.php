<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\Storage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\Storage\SspAssetToModelWritePublisherPlugin;
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
 * @group SspAsset
 * @group SspAssetToModelWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class SspAssetToModelWritePublisherPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SSP_ASSET_NAME_1 = 'Test Asset 1';

    /**
     * @var string
     */
    protected const SSP_ASSET_SERIAL_NUMBER_1 = 'SN001TEST';

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
    protected const COMPANY_NAME_1 = 'Test Company 1';

    /**
     * @var string
     */
    protected const BUSINESS_UNIT_NAME_1 = 'Test Business Unit 1';

    /**
     * @var string
     */
    protected const STORAGE_FIELD_ID_ASSET = 'id_asset';

    /**
     * @var string
     */
    protected const STORAGE_FIELD_BUSINESS_UNIT_IDS = 'business_unit_ids';

    /**
     * @var string
     */
    protected const STORAGE_FIELD_NAME = 'name';

    /**
     * @var string
     */
    protected const STORAGE_FIELD_COMPANY_IDS = 'company_ids';

    /**
     * @var string
     */
    protected const STORAGE_FIELD_SERIAL_NUMBER = 'serial_number';

    /**
     * @var string
     */
    protected const STORAGE_FIELD_MODEL_IDS = 'model_ids';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\SspAssetToModelWritePublisherPlugin
     */
    protected SspAssetToModelWritePublisherPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new SspAssetToModelWritePublisherPlugin();
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->clearSspAssetStorageData();
        $this->tester->clearSspAssetData();
    }

    public function testHandlesBulkPublishesStorageDataOnCreateEvent(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::NAME => static::COMPANY_NAME_1,
        ]);

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => static::BUSINESS_UNIT_NAME_1,
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_1,
        ]);

        $sspAssetTransfer = $this->tester->haveSspAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $assignmentEntity = $this->tester->haveSspAssetToModelAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $sspModelTransfer->getIdSspModelOrFail(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($assignmentEntity->getIdSspAssetToSspModel())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_ssp_model.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNotNull($storageData);
        $this->assertSame($sspAssetTransfer->getIdSspAsset(), $storageData[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertSame(static::SSP_ASSET_NAME_1, $storageData[static::STORAGE_FIELD_NAME]);
        $this->assertSame(static::SSP_ASSET_SERIAL_NUMBER_1, $storageData[static::STORAGE_FIELD_SERIAL_NUMBER]);
        $this->assertSame([$sspModelTransfer->getIdSspModelOrFail()], $storageData[static::STORAGE_FIELD_MODEL_IDS]);
    }

    public function testHandlesBulkPublishesStorageDataOnUpdateEvent(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::NAME => static::COMPANY_NAME_1,
        ]);

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => static::BUSINESS_UNIT_NAME_1,
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_1,
        ]);

        $sspAssetTransfer = $this->tester->haveSspAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $assignmentEntity = $this->tester->haveSspAssetToModelAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $sspModelTransfer->getIdSspModelOrFail(),
        );

        $this->tester->haveSspAssetStorage($sspAssetTransfer->getIdSspAssetOrFail(), []);

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($assignmentEntity->getIdSspAssetToSspModel())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_UPDATE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_ssp_model.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_UPDATE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNotNull($storageData);
        $this->assertSame($sspAssetTransfer->getIdSspAsset(), $storageData[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertSame(static::SSP_ASSET_NAME_1, $storageData[static::STORAGE_FIELD_NAME]);
        $this->assertSame([$sspModelTransfer->getIdSspModelOrFail()], $storageData[static::STORAGE_FIELD_MODEL_IDS]);
    }

    public function testHandlesBulkPublishesStorageDataOnDeleteEvent(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::NAME => static::COMPANY_NAME_1,
        ]);

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => static::BUSINESS_UNIT_NAME_1,
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $sspAssetTransfer = $this->tester->haveSspAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $this->tester->haveSspAssetStorage($sspAssetTransfer->getIdSspAssetOrFail(), []);
        $this->tester->clearSspAssetData();

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId(1)
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_DELETE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_ssp_model.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_DELETE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNull($storageData);
    }

    public function testHandlesBulkWithMultipleModelAssignments(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::NAME => static::COMPANY_NAME_1,
        ]);

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => static::BUSINESS_UNIT_NAME_1,
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $sspModelTransfer1 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_1,
        ]);

        $sspModelTransfer2 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_2,
        ]);

        $sspAssetTransfer = $this->tester->haveSspAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $assignmentEntity1 = $this->tester->haveSspAssetToModelAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $sspModelTransfer1->getIdSspModelOrFail(),
        );

        $assignmentEntity2 = $this->tester->haveSspAssetToModelAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $sspModelTransfer2->getIdSspModelOrFail(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($assignmentEntity1->getIdSspAssetToSspModel())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_ssp_model.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
            (new EventEntityTransfer())
                ->setId($assignmentEntity2->getIdSspAssetToSspModel())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_ssp_model.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNotNull($storageData);
        $this->assertSame($sspAssetTransfer->getIdSspAsset(), $storageData[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertCount(2, $storageData[static::STORAGE_FIELD_MODEL_IDS]);
        $this->assertContains($sspModelTransfer1->getIdSspModelOrFail(), $storageData[static::STORAGE_FIELD_MODEL_IDS]);
        $this->assertContains($sspModelTransfer2->getIdSspModelOrFail(), $storageData[static::STORAGE_FIELD_MODEL_IDS]);
    }

    public function testHandlesBulkWithEmptyEvents(): void
    {
        // Arrange
        // Act
        $this->plugin->handleBulk([], SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE);

        // Assert
        $this->assertTrue(true);
    }

    public function testHandlesBulkUpdatesExistingStorageData(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::NAME => static::COMPANY_NAME_1,
        ]);

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => static::BUSINESS_UNIT_NAME_1,
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => static::SSP_MODEL_NAME_1,
        ]);

        $sspAssetTransfer = $this->tester->haveSspAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $this->tester->haveSspAssetStorage($sspAssetTransfer->getIdSspAssetOrFail(), []);

        $this->tester->haveSspAssetToCompanyBusinessUnitAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        );

        $assignmentEntity = $this->tester->haveSspAssetToModelAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $sspModelTransfer->getIdSspModelOrFail(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($assignmentEntity->getIdSspAssetToSspModel())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_UPDATE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_ssp_model.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_UPDATE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNotNull($storageData);
        $this->assertSame($sspAssetTransfer->getIdSspAsset(), $storageData[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertSame([$sspModelTransfer->getIdSspModelOrFail()], $storageData[static::STORAGE_FIELD_MODEL_IDS]);
        $this->assertSame([$companyBusinessUnitTransfer->getIdCompanyBusinessUnit()], $storageData[static::STORAGE_FIELD_BUSINESS_UNIT_IDS]);
    }

    public function testHandlesBulkDeletesStorageForNonExistentAsset(): void
    {
        // Arrange
        $nonExistentAssetId = 99999;
        $this->tester->haveSspAssetStorage($nonExistentAssetId, []);

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId(1)
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_DELETE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_ssp_model.fk_ssp_asset' => $nonExistentAssetId,
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_DELETE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($nonExistentAssetId);
        $this->assertNull($storageData);
    }

    public function testGetSubscribedEventsReturnsCorrectEvents(): void
    {
        // Arrange
        // Act
        $subscribedEvents = $this->plugin->getSubscribedEvents();

        // Assert
        $expectedEvents = [
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_UPDATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_DELETE,
        ];

        $this->assertSame($expectedEvents, $subscribedEvents);
    }
}
