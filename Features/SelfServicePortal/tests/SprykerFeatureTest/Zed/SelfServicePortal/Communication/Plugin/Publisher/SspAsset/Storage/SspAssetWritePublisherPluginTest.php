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
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\Storage\SspAssetWritePublisherPlugin;
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
 * @group SspAssetWritePublisherPluginTest
 * Add your own group annotations below this line
 */
class SspAssetWritePublisherPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SSP_ASSET_NAME_1 = 'Test Asset 1';

    /**
     * @var string
     */
    protected const SSP_ASSET_NAME_2 = 'Test Asset 2';

    /**
     * @var string
     */
    protected const SSP_ASSET_SERIAL_NUMBER_1 = 'SN001TEST';

    /**
     * @var string
     */
    protected const SSP_ASSET_SERIAL_NUMBER_2 = 'SN002TEST';

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
     * @var string
     */
    protected const STORAGE_FIELD_ID_OWNER_BUSINESS_UNIT = 'id_owner_business_unit';

    /**
     * @var string
     */
    protected const STORAGE_FIELD_ID_OWNER_COMPANY_ID = 'id_owner_company_id';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\SspAssetWritePublisherPlugin
     */
    protected SspAssetWritePublisherPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new SspAssetWritePublisherPlugin();
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->tester->clearSspAssetStorageData();
        $this->tester->ensureSspAssetRelatedTablesAreEmpty();
    }

    public function testHandlesBulkPublishesStorageDataCorrectly(): void
    {
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

        $sspAssetTransfer1 = $this->tester->haveAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $sspAssetTransfer2 = $this->tester->haveAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_2,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_2,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $this->tester->haveSspAssetToModelAssignment(
            $sspAssetTransfer1->getIdSspAssetOrFail(),
            $sspModelTransfer->getIdSspModelOrFail(),
        );

        $this->tester->haveSspAssetToCompanyBusinessUnitAssignment(
            $sspAssetTransfer1->getIdSspAssetOrFail(),
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($sspAssetTransfer1->getIdSspAssetOrFail()),
            (new EventEntityTransfer())->setId($sspAssetTransfer2->getIdSspAssetOrFail()),
        ];

        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::SSP_ASSET_PUBLISH);
        $storageData1 = $this->tester->findSspAssetStorageData($sspAssetTransfer1->getIdSspAsset());
        $this->assertNotNull($storageData1);
        $this->assertSame($sspAssetTransfer1->getIdSspAsset(), $storageData1[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertSame(static::SSP_ASSET_NAME_1, $storageData1[static::STORAGE_FIELD_NAME]);
        $this->assertSame(static::SSP_ASSET_SERIAL_NUMBER_1, $storageData1[static::STORAGE_FIELD_SERIAL_NUMBER]);
        $this->assertSame([$companyBusinessUnitTransfer->getIdCompanyBusinessUnit()], $storageData1[static::STORAGE_FIELD_BUSINESS_UNIT_IDS]);
        $this->assertSame([$companyTransfer->getIdCompany()], $storageData1[static::STORAGE_FIELD_COMPANY_IDS]);
        $this->assertSame([$sspModelTransfer->getIdSspModel()], $storageData1[static::STORAGE_FIELD_MODEL_IDS]);
        $this->assertSame($companyBusinessUnitTransfer->getIdCompanyBusinessUnit(), $storageData1[static::STORAGE_FIELD_ID_OWNER_BUSINESS_UNIT]);
        $this->assertSame($companyTransfer->getIdCompany(), $storageData1[static::STORAGE_FIELD_ID_OWNER_COMPANY_ID]);

        $storageData2 = $this->tester->findSspAssetStorageData($sspAssetTransfer2->getIdSspAsset());
        $this->assertNotNull($storageData2);
        $this->assertSame($sspAssetTransfer2->getIdSspAsset(), $storageData2[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertSame(static::SSP_ASSET_NAME_2, $storageData2[static::STORAGE_FIELD_NAME]);
        $this->assertSame(static::SSP_ASSET_SERIAL_NUMBER_2, $storageData2[static::STORAGE_FIELD_SERIAL_NUMBER]);
        $this->assertSame([], $storageData2[static::STORAGE_FIELD_MODEL_IDS]); // No model assignments
    }

    public function testHandlesBulkWithMultipleModelAssignments(): void
    {
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::NAME => static::COMPANY_NAME_1,
        ]);

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => static::BUSINESS_UNIT_NAME_1,
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $sspModelTransfer1 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Test Model 1',
        ]);

        $sspModelTransfer2 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Test Model 2',
        ]);

        $sspModelTransfer3 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Test Model 3',
        ]);

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $this->tester->haveSspAssetToModelAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $sspModelTransfer1->getIdSspModelOrFail(),
        );
        $this->tester->haveSspAssetToModelAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $sspModelTransfer2->getIdSspModelOrFail(),
        );
        $this->tester->haveSspAssetToModelAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $sspModelTransfer3->getIdSspModelOrFail(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($sspAssetTransfer->getIdSspAssetOrFail()),
        ];

        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::SSP_ASSET_PUBLISH);

        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNotNull($storageData);
        $this->assertSame($sspAssetTransfer->getIdSspAsset(), $storageData[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertSame([
            $sspModelTransfer1->getIdSspModel(),
            $sspModelTransfer2->getIdSspModel(),
            $sspModelTransfer3->getIdSspModel(),
        ], $storageData[static::STORAGE_FIELD_MODEL_IDS]);
    }

    public function testHandlesBulkWithEmptyEvents(): void
    {
        $this->plugin->handleBulk([], SelfServicePortalConfig::SSP_ASSET_PUBLISH);

        $this->assertTrue(true);
    }

    public function testHandlesBulkUpdatesExistingStorageData(): void
    {
        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::NAME => static::COMPANY_NAME_1,
        ]);

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => static::BUSINESS_UNIT_NAME_1,
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $sspModelTransfer1 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Test Model 1',
        ]);

        $sspModelTransfer2 = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Test Model 2',
        ]);

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::NAME => static::SSP_ASSET_NAME_1,
            SspAssetTransfer::SERIAL_NUMBER => static::SSP_ASSET_SERIAL_NUMBER_1,
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $this->tester->haveSspAssetStorage($sspAssetTransfer->getIdSspAssetOrFail(), [999]);

        $this->tester->haveSspAssetToModelAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $sspModelTransfer1->getIdSspModelOrFail(),
        );
        $this->tester->haveSspAssetToModelAssignment(
            $sspAssetTransfer->getIdSspAssetOrFail(),
            $sspModelTransfer2->getIdSspModelOrFail(),
        );

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($sspAssetTransfer->getIdSspAssetOrFail()),
        ];

        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::SSP_ASSET_PUBLISH);

        $storageData = $this->tester->findSspAssetStorageData($sspAssetTransfer->getIdSspAsset());
        $this->assertNotNull($storageData);
        $this->assertSame($sspAssetTransfer->getIdSspAsset(), $storageData[static::STORAGE_FIELD_ID_ASSET]);
        $this->assertCount(2, $storageData[static::STORAGE_FIELD_MODEL_IDS]);
        $this->assertContains($sspModelTransfer1->getIdSspModelOrFail(), $storageData[static::STORAGE_FIELD_MODEL_IDS]);
        $this->assertContains($sspModelTransfer2->getIdSspModelOrFail(), $storageData[static::STORAGE_FIELD_MODEL_IDS]);
        $this->assertNotContains(999, $storageData[static::STORAGE_FIELD_MODEL_IDS]);
    }

    public function testHandlesBulkDeletesStorageForNonExistentAsset(): void
    {
        $nonExistentAssetId = 99999;
        $this->tester->haveSspAssetStorage($nonExistentAssetId, [1, 2, 3]);

        $eventEntityTransfers = [
            (new EventEntityTransfer())->setId($nonExistentAssetId),
        ];

        $this->plugin->handleBulk($eventEntityTransfers, SelfServicePortalConfig::SSP_ASSET_PUBLISH);

        $storageData = $this->tester->findSspAssetStorageData($nonExistentAssetId);
        $this->assertNull($storageData);
    }
}
