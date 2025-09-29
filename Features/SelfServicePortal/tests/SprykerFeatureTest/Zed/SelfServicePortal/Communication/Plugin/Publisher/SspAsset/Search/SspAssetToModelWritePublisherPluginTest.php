<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\Search;

use Codeception\Test\Unit;
use Generated\Shared\Search\SspAssetIndexMap;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetSearchQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Spryker\Zed\Store\StoreDependencyProvider;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\Search\SspAssetToModelWritePublisherPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group SspAssetToModelWritePublisherPluginTest
 */
class SspAssetToModelWritePublisherPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @uses \Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetToSspModelTableMap::COL_FK_SSP_ASSET
     *
     * @var string
     */
    protected const COL_FK_SSP_ASSET = 'spy_ssp_asset_to_ssp_model.fk_ssp_asset';

    /**
     * @uses \Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetToSspModelTableMap::COL_FK_SSP_MODEL
     *
     * @var string
     */
    protected const COL_FK_SSP_MODEL = 'spy_ssp_asset_to_ssp_model.fk_ssp_model';

    protected SelfServicePortalCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $storeTransferAT = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_NAME_AT,
        ]);

        $storeTransferDE = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_NAME_DE,
        ]);

        $storeFacadeMock = $this->createMock(StoreFacadeInterface::class);
        $storeFacadeMock->method('getAllStores')->willReturn([
            $storeTransferAT,
            $storeTransferDE,
        ]);

        $this->tester->setDependency(
            SelfServicePortalDependencyProvider::FACADE_STORE,
            $storeFacadeMock,
        );

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
            static::STORE_NAME_DE,
        );

        $this->tester->ensureSspAssetRelatedTablesAreEmpty();
    }

    public function testHandleBulkWritesSspAssetSearchDataOnModelAssignmentCreate(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);

        $sspModelTransfer1 = $this->tester->haveSspModel();
        $sspModelTransfer2 = $this->tester->haveSspModel();

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
            SspAssetTransfer::SSP_MODELS => [
                $sspModelTransfer1->modifiedToArray(false, true),
            ],
        ]);

        // Act
        $sspAssetToModelWritePublisherPlugin = new SspAssetToModelWritePublisherPlugin();
        $sspAssetToModelWritePublisherPlugin->handleBulk(
            [
                (new EventEntityTransfer())
                    ->setForeignKeys([
                        static::COL_FK_SSP_ASSET => $sspAssetTransfer->getIdSspAsset(),
                        static::COL_FK_SSP_MODEL => $sspModelTransfer2->getIdSspModel(),
                    ]),
            ],
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE,
        );

        // Assert
        $sspAssetSearchEntity = SpySspAssetSearchQuery::create()
            ->filterByFkSspAsset($sspAssetTransfer->getIdSspAsset())
            ->findOne();

        $this->assertNotNull($sspAssetSearchEntity);
        $this->assertSame($sspAssetTransfer->getIdSspAsset(), $sspAssetSearchEntity->getFkSspAsset());

        $searchData = $sspAssetSearchEntity->getData();
        $this->assertArrayHasKey(SspAssetIndexMap::SEARCH_RESULT_DATA, $searchData);

        $resultData = $searchData[SspAssetIndexMap::SEARCH_RESULT_DATA];
        $this->assertArrayHasKey('model_ids', $resultData);
        $this->assertContains($sspModelTransfer1->getIdSspModel(), $resultData['model_ids']);
    }

    public function testHandleBulkWritesSspAssetSearchDataOnModelAssignmentDelete(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);

        $sspModelTransfer1 = $this->tester->haveSspModel();
        $sspModelTransfer2 = $this->tester->haveSspModel();

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
            SspAssetTransfer::SSP_MODELS => [
                $sspModelTransfer1->modifiedToArray(false, true),
                $sspModelTransfer2->modifiedToArray(false, true),
            ],
        ]);

        $this->tester->ensureSspAssetRelatedTablesAreEmpty();

        // Act
        $sspAssetToModelWritePublisherPlugin = new SspAssetToModelWritePublisherPlugin();
        $sspAssetToModelWritePublisherPlugin->handleBulk(
            [
                (new EventEntityTransfer())
                    ->setForeignKeys([
                        static::COL_FK_SSP_ASSET => $sspAssetTransfer->getIdSspAsset(),
                        static::COL_FK_SSP_MODEL => $sspModelTransfer2->getIdSspModel(),
                    ]),
            ],
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_DELETE,
        );

        // Assert
        $sspAssetSearchEntity = SpySspAssetSearchQuery::create()
            ->filterByFkSspAsset($sspAssetTransfer->getIdSspAsset())
            ->findOne();

        $this->assertNull($sspAssetSearchEntity);
    }

    public function testHandleBulkUpdatesFullTextBoostedAndSuggestionTermsWithModelNames(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);

        $sspModelTransfer = $this->tester->haveSspModel([
            SspModelTransfer::NAME => 'Test Model Name',
        ]);

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::NAME => 'Test Asset Name',
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
            SspAssetTransfer::SSP_MODELS => [
                $sspModelTransfer->modifiedToArray(false, true),
            ],
        ]);

        // Act
        $sspAssetToModelWritePublisherPlugin = new SspAssetToModelWritePublisherPlugin();
        $sspAssetToModelWritePublisherPlugin->handleBulk(
            [
                (new EventEntityTransfer())
                    ->setForeignKeys([
                        static::COL_FK_SSP_ASSET => $sspAssetTransfer->getIdSspAsset(),
                        static::COL_FK_SSP_MODEL => $sspModelTransfer->getIdSspModel(),
                    ]),
            ],
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE,
        );

        // Assert
        $sspAssetSearchEntity = SpySspAssetSearchQuery::create()
            ->filterByFkSspAsset($sspAssetTransfer->getIdSspAsset())
            ->findOne();

        $this->assertNotNull($sspAssetSearchEntity);

        $searchData = $sspAssetSearchEntity->getData();
        $this->assertArrayHasKey(SspAssetIndexMap::FULL_TEXT_BOOSTED, $searchData);
        $this->assertArrayHasKey(SspAssetIndexMap::SUGGESTION_TERMS, $searchData);
        $this->assertArrayHasKey(SspAssetIndexMap::COMPLETION_TERMS, $searchData);

        $this->assertContains('Test Asset Name', $searchData[SspAssetIndexMap::FULL_TEXT_BOOSTED]);
        $this->assertContains('Test Model Name', $searchData[SspAssetIndexMap::FULL_TEXT_BOOSTED]);

        $this->assertContains('Test Asset Name', $searchData[SspAssetIndexMap::SUGGESTION_TERMS]);
        $this->assertContains('Test Model Name', $searchData[SspAssetIndexMap::SUGGESTION_TERMS]);

        $this->assertContains('Test Asset Name', $searchData[SspAssetIndexMap::COMPLETION_TERMS]);
        $this->assertContains('Test Model Name', $searchData[SspAssetIndexMap::COMPLETION_TERMS]);
    }

    public function testHandleBulkWithMultipleEventEntityTransfers(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);

        $sspModelTransfer1 = $this->tester->haveSspModel();
        $sspModelTransfer2 = $this->tester->haveSspModel();

        $sspAssetTransfer1 = $this->tester->haveAsset([
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
        ]);
        $sspAssetTransfer2 = $this->tester->haveAsset([
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
        ]);

        // Act
        $sspAssetToModelWritePublisherPlugin = new SspAssetToModelWritePublisherPlugin();
        $sspAssetToModelWritePublisherPlugin->handleBulk(
            [
                (new EventEntityTransfer())
                    ->setForeignKeys([
                        static::COL_FK_SSP_ASSET => $sspAssetTransfer1->getIdSspAsset(),
                        static::COL_FK_SSP_MODEL => $sspModelTransfer1->getIdSspModel(),
                    ]),
                (new EventEntityTransfer())
                    ->setForeignKeys([
                        static::COL_FK_SSP_ASSET => $sspAssetTransfer2->getIdSspAsset(),
                        static::COL_FK_SSP_MODEL => $sspModelTransfer2->getIdSspModel(),
                    ]),
            ],
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE,
        );

        // Assert
        $sspAssetSearchEntities = SpySspAssetSearchQuery::create()
            ->filterByFkSspAsset_In([
                $sspAssetTransfer1->getIdSspAsset(),
                $sspAssetTransfer2->getIdSspAsset(),
            ])
            ->orderByFkSspAsset()
            ->find();

        $this->assertCount(2, $sspAssetSearchEntities);

        $sspAssetSearchEntity1 = $sspAssetSearchEntities[0];
        $sspAssetSearchEntity2 = $sspAssetSearchEntities[1];

        $this->assertSame($sspAssetTransfer1->getIdSspAsset(), $sspAssetSearchEntity1->getFkSspAsset());
        $this->assertSame($sspAssetTransfer2->getIdSspAsset(), $sspAssetSearchEntity2->getFkSspAsset());

        $this->assertSearchDataStructure($sspAssetSearchEntity1->getData());
        $this->assertSearchDataStructure($sspAssetSearchEntity2->getData());
    }

    public function testGetSubscribedEventsReturnsCorrectEvents(): void
    {
        // Arrange
        $sspAssetToModelWritePublisherPlugin = new SspAssetToModelWritePublisherPlugin();

        // Act
        $subscribedEvents = $sspAssetToModelWritePublisherPlugin->getSubscribedEvents();

        // Assert
        $expectedEvents = [
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_CREATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_UPDATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_MODEL_DELETE,
        ];

        $this->assertSame($expectedEvents, $subscribedEvents);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return void
     */
    protected function assertSearchDataStructure(array $data): void
    {
        $this->assertArrayHasKey(SspAssetIndexMap::TYPE, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::SEARCH_RESULT_DATA, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::FULL_TEXT_BOOSTED, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::SUGGESTION_TERMS, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::COMPLETION_TERMS, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::BUSINESS_UNIT_IDS, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::COMPANY_IDS, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::ID_OWNER_BUSINESS_UNIT, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::ID_OWNER_COMPANY_ID, $data);

        $this->assertSame(SelfServicePortalConfig::SSP_ASSET_RESOURCE_NAME, $data[SspAssetIndexMap::TYPE]);
    }
}
