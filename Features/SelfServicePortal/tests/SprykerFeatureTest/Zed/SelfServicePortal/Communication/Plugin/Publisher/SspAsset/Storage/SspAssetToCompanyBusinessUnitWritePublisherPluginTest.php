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
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\Storage\SspAssetToCompanyBusinessUnitWritePublisherPlugin;
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
 * @group SspAssetToCompanyBusinessUnitWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class SspAssetToCompanyBusinessUnitWritePublisherPluginTest extends Unit
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
    protected const COMPANY_NAME_1 = 'Test Company 1';

    /**
     * @var string
     */
    protected const BUSINESS_UNIT_NAME_1 = 'Test Business Unit 1';

    /**
     * @var string
     */
    protected const BUSINESS_UNIT_NAME_2 = 'Test Business Unit 2';

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
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\SspAssetToCompanyBusinessUnitWritePublisherPlugin
     */
    protected SspAssetToCompanyBusinessUnitWritePublisherPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new SspAssetToCompanyBusinessUnitWritePublisherPlugin();
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->clearSspAssetStorageData();
        $this->tester->ensureSspAssetRelatedTablesAreEmpty();
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

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $assignmentEntity = $this->tester->haveSspAssetToCompanyBusinessUnitAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($assignmentEntity->getIdSspAssetToCompanyBusinessUnit())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_company_business_unit.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNotNull($storageData);
        $this->assertSame($sspAssetTransfer->getIdSspAsset(), $storageData[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertSame(static::SSP_ASSET_NAME_1, $storageData[static::STORAGE_FIELD_NAME]);
        $this->assertSame(static::SSP_ASSET_SERIAL_NUMBER_1, $storageData[static::STORAGE_FIELD_SERIAL_NUMBER]);
        $this->assertSame([$companyBusinessUnitTransfer->getIdCompanyBusinessUnit()], $storageData[static::STORAGE_FIELD_BUSINESS_UNIT_IDS]);
        $this->assertSame([$companyTransfer->getIdCompany()], $storageData[static::STORAGE_FIELD_COMPANY_IDS]);
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

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $assignmentEntity = $this->tester->haveSspAssetToCompanyBusinessUnitAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        );

        $this->tester->haveSspAssetStorage($sspAssetTransfer->getIdSspAssetOrFail(), [$companyBusinessUnitTransfer->getIdCompanyBusinessUnit()]);

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($assignmentEntity->getIdSspAssetToCompanyBusinessUnit())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_UPDATE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_company_business_unit.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_UPDATE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNotNull($storageData);
        $this->assertSame($sspAssetTransfer->getIdSspAsset(), $storageData[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertSame(static::SSP_ASSET_NAME_1, $storageData[static::STORAGE_FIELD_NAME]);
        $this->assertSame([$companyBusinessUnitTransfer->getIdCompanyBusinessUnit()], $storageData[static::STORAGE_FIELD_BUSINESS_UNIT_IDS]);
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

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $this->tester->haveSspAssetStorage($sspAssetTransfer->getIdSspAssetOrFail(), [$companyBusinessUnitTransfer->getIdCompanyBusinessUnit()]);
        $this->tester->ensureSspAssetRelatedTablesAreEmpty();

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId(1)
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_DELETE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_company_business_unit.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_DELETE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNull($storageData);
    }

    public function testHandlesBulkWithMultipleBusinessUnitAssignments(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::NAME => static::COMPANY_NAME_1,
        ]);

        $companyBusinessUnitTransfer1 = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => static::BUSINESS_UNIT_NAME_1,
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $companyBusinessUnitTransfer2 = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => static::BUSINESS_UNIT_NAME_2,
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer1,
        ]);

        $assignmentEntity1 = $this->tester->haveSspAssetToCompanyBusinessUnitAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $companyBusinessUnitTransfer1->getIdCompanyBusinessUnit(),
        );

        $assignmentEntity2 = $this->tester->haveSspAssetToCompanyBusinessUnitAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $companyBusinessUnitTransfer2->getIdCompanyBusinessUnit(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($assignmentEntity1->getIdSspAssetToCompanyBusinessUnit())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_company_business_unit.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
            (new EventEntityTransfer())
                ->setId($assignmentEntity2->getIdSspAssetToCompanyBusinessUnit())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_company_business_unit.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNotNull($storageData);
        $this->assertSame($sspAssetTransfer->getIdSspAsset(), $storageData[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertCount(2, $storageData[static::STORAGE_FIELD_BUSINESS_UNIT_IDS]);
        $this->assertContains($companyBusinessUnitTransfer1->getIdCompanyBusinessUnit(), $storageData[static::STORAGE_FIELD_BUSINESS_UNIT_IDS]);
        $this->assertContains($companyBusinessUnitTransfer2->getIdCompanyBusinessUnit(), $storageData[static::STORAGE_FIELD_BUSINESS_UNIT_IDS]);
    }

    public function testHandlesBulkWithEmptyEvents(): void
    {
        // Arrange
        // Act
        $this->plugin->handleBulk([], SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE);

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

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $this->tester->haveSspAssetStorage($sspAssetTransfer->getIdSspAssetOrFail(), [999]);

        $this->tester->haveSspAssetToModelAttachment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $sspModelTransfer->getIdSspModelOrFail(),
        );

        $assignmentEntity = $this->tester->haveSspAssetToCompanyBusinessUnitAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId($assignmentEntity->getIdSspAssetToCompanyBusinessUnit())
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_UPDATE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_company_business_unit.fk_ssp_asset' => $sspAssetTransfer->getIdSspAssetOrFail(),
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_UPDATE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNotNull($storageData);
        $this->assertSame($sspAssetTransfer->getIdSspAsset(), $storageData[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertSame([$companyBusinessUnitTransfer->getIdCompanyBusinessUnit()], $storageData[static::STORAGE_FIELD_BUSINESS_UNIT_IDS]);
        $this->assertSame([$sspModelTransfer->getIdSspModelOrFail()], $storageData[static::STORAGE_FIELD_MODEL_IDS]);
        $this->assertNotContains(999, $storageData[static::STORAGE_FIELD_BUSINESS_UNIT_IDS]);
    }

    public function testHandlesBulkDeletesStorageForNonExistentAsset(): void
    {
        // Arrange
        $nonExistentAssetId = 99999;
        $this->tester->haveSspAssetStorage($nonExistentAssetId, [1, 2, 3]);

        $eventEntityTransfers = [
            (new EventEntityTransfer())
                ->setId(1)
                ->setEvent(SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_DELETE)
                ->setForeignKeys([
                    'spy_ssp_asset_to_company_business_unit.fk_ssp_asset' => $nonExistentAssetId,
                ]),
        ];

        // Act
        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_DELETE);

        // Assert
        $storageData = $this->tester->findSspAssetStorageData($nonExistentAssetId);
        $this->assertNull($storageData);
    }

    public function testGetSubscribedEventsReturnsCorrectEvents(): void
    {
        // Arrange
        $subscribedEvents = $this->plugin->getSubscribedEvents();

        // Expect
        $expectedEvents = [
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_UPDATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_DELETE,
        ];

         // Assert
        $this->assertSame($expectedEvents, $subscribedEvents);
    }
}
