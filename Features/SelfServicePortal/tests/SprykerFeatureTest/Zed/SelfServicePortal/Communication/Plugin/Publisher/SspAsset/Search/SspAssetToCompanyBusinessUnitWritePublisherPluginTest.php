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
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetSearchQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Spryker\Zed\Store\StoreDependencyProvider;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\Search\SspAssetToCompanyBusinessUnitWritePublisherPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group SspAssetToCompanyBusinessUnitWritePublisherPluginTest
 */
class SspAssetToCompanyBusinessUnitWritePublisherPluginTest extends Unit
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
     * @uses \Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetToCompanyBusinessUnitTableMap::COL_FK_SSP_ASSET
     *
     * @var string
     */
    protected const COL_FK_SSP_ASSET = 'spy_ssp_asset_to_company_business_unit.fk_ssp_asset';

    /**
     * @uses \Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetToCompanyBusinessUnitTableMap::COL_FK_COMPANY_BUSINESS_UNIT
     *
     * @var string
     */
    protected const COL_FK_COMPANY_BUSINESS_UNIT = 'spy_ssp_asset_to_company_business_unit.fk_company_business_unit';

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

    public function testHandleBulkWritesSspAssetSearchDataOnBusinessUnitAssignmentCreate(): void
    {
        // Arrange
        $companyTransfer1 = $this->tester->haveCompany();
        $companyTransfer2 = $this->tester->haveCompany();

        $businessUnitTransfer1 = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer1->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer1,
        ]);

        $businessUnitTransfer2 = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer2->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer2,
        ]);

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer1,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer1],
            ],
        ]);

        // Act
        $sspAssetToCompanyBusinessUnitWritePublisherPlugin = new SspAssetToCompanyBusinessUnitWritePublisherPlugin();
        $sspAssetToCompanyBusinessUnitWritePublisherPlugin->handleBulk(
            [
                (new EventEntityTransfer())
                    ->setForeignKeys([
                        static::COL_FK_SSP_ASSET => $sspAssetTransfer->getIdSspAsset(),
                        static::COL_FK_COMPANY_BUSINESS_UNIT => $businessUnitTransfer2->getIdCompanyBusinessUnit(),
                    ]),
            ],
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE,
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
        $this->assertArrayHasKey('business_unit_ids', $resultData);
        $this->assertContains($businessUnitTransfer1->getIdCompanyBusinessUnit(), $resultData['business_unit_ids']);

        $this->assertArrayHasKey('company_ids', $resultData);
        $this->assertContains($companyTransfer1->getIdCompany(), $resultData['company_ids']);
    }

    public function testHandleBulkWritesSspAssetSearchDataOnBusinessUnitAssignmentDelete(): void
    {
        // Arrange
        $companyTransfer1 = $this->tester->haveCompany();
        $companyTransfer2 = $this->tester->haveCompany();

        $businessUnitTransfer1 = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer1->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer1,
        ]);

        $businessUnitTransfer2 = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer2->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer2,
        ]);

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer1,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer1],
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer2],
            ],
        ]);

        $this->tester->haveSspAssetSearch([
            'fk_ssp_asset' => $sspAssetTransfer->getIdSspAsset(),
            'data' => '{"type":"ssp_asset","name":"A1","store":"DE"}',
            'structured_data' => '{"type":"ssp_asset","name":"A1","store":"DE"}',
            'key' => 'key:1',
        ]);

        $this->tester->ensureSspAssetRelatedTablesAreEmpty();

        // Act
        $sspAssetToCompanyBusinessUnitWritePublisherPlugin = new SspAssetToCompanyBusinessUnitWritePublisherPlugin();
        $sspAssetToCompanyBusinessUnitWritePublisherPlugin->handleBulk(
            [
                (new EventEntityTransfer())
                    ->setForeignKeys([
                        static::COL_FK_SSP_ASSET => $sspAssetTransfer->getIdSspAsset(),
                        static::COL_FK_COMPANY_BUSINESS_UNIT => $businessUnitTransfer2->getIdCompanyBusinessUnit(),
                    ]),
            ],
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_DELETE,
        );

        // Assert
        $sspAssetSearchEntity = SpySspAssetSearchQuery::create()
            ->filterByFkSspAsset($sspAssetTransfer->getIdSspAsset())
            ->findOne();

        $this->assertNull($sspAssetSearchEntity);
    }

    public function testHandleBulkUpdatesOwnerBusinessUnitAndCompanyInformation(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $businessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);

        $additionalBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer,
        ]);

        $sspAssetTransfer = $this->tester->haveAsset([
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer],
            ],
        ]);

        // Act
        $sspAssetToCompanyBusinessUnitWritePublisherPlugin = new SspAssetToCompanyBusinessUnitWritePublisherPlugin();
        $sspAssetToCompanyBusinessUnitWritePublisherPlugin->handleBulk(
            [
                (new EventEntityTransfer())
                    ->setForeignKeys([
                        static::COL_FK_SSP_ASSET => $sspAssetTransfer->getIdSspAsset(),
                        static::COL_FK_COMPANY_BUSINESS_UNIT => $additionalBusinessUnitTransfer->getIdCompanyBusinessUnit(),
                    ]),
            ],
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE,
        );

        // Assert
        $sspAssetSearchEntity = SpySspAssetSearchQuery::create()
            ->filterByFkSspAsset($sspAssetTransfer->getIdSspAsset())
            ->findOne();

        $this->assertNotNull($sspAssetSearchEntity);

        $searchData = $sspAssetSearchEntity->getData();
        $this->assertArrayHasKey(SspAssetIndexMap::ID_OWNER_BUSINESS_UNIT, $searchData);
        $this->assertArrayHasKey(SspAssetIndexMap::ID_OWNER_COMPANY_ID, $searchData);

        $this->assertSame($businessUnitTransfer->getIdCompanyBusinessUnit(), $searchData[SspAssetIndexMap::ID_OWNER_BUSINESS_UNIT]);
        $this->assertSame($companyTransfer->getIdCompany(), $searchData[SspAssetIndexMap::ID_OWNER_COMPANY_ID]);

        $this->assertArrayHasKey(SspAssetIndexMap::BUSINESS_UNIT_IDS, $searchData);
        $this->assertArrayHasKey(SspAssetIndexMap::COMPANY_IDS, $searchData);

        $this->assertContains($businessUnitTransfer->getIdCompanyBusinessUnit(), $searchData[SspAssetIndexMap::BUSINESS_UNIT_IDS]);
        $this->assertContains($companyTransfer->getIdCompany(), $searchData[SspAssetIndexMap::COMPANY_IDS]);
    }

    public function testHandleBulkWithMultipleEventEntityTransfers(): void
    {
        // Arrange
        $companyTransfer1 = $this->tester->haveCompany();
        $companyTransfer2 = $this->tester->haveCompany();

        $businessUnitTransfer1 = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer1->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer1,
        ]);

        $businessUnitTransfer2 = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer2->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer2,
        ]);

        $businessUnitTransfer3 = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer1->getIdCompany(),
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer1,
        ]);

        $sspAssetTransfer1 = $this->tester->haveAsset([
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer1,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer1],
            ],
        ]);

        $sspAssetTransfer2 = $this->tester->haveAsset([
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer2,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer2],
            ],
        ]);

        // Act
        $sspAssetToCompanyBusinessUnitWritePublisherPlugin = new SspAssetToCompanyBusinessUnitWritePublisherPlugin();
        $sspAssetToCompanyBusinessUnitWritePublisherPlugin->handleBulk(
            [
                (new EventEntityTransfer())
                    ->setForeignKeys([
                        static::COL_FK_SSP_ASSET => $sspAssetTransfer1->getIdSspAsset(),
                        static::COL_FK_COMPANY_BUSINESS_UNIT => $businessUnitTransfer3->getIdCompanyBusinessUnit(),
                    ]),
                (new EventEntityTransfer())
                    ->setForeignKeys([
                        static::COL_FK_SSP_ASSET => $sspAssetTransfer2->getIdSspAsset(),
                        static::COL_FK_COMPANY_BUSINESS_UNIT => $businessUnitTransfer1->getIdCompanyBusinessUnit(),
                    ]),
            ],
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE,
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

    public function testHandleBulkWithEmptyEventEntityTransfers(): void
    {
        // Arrange
        $this->tester->ensureSspAssetSearchTableIsEmpty();

        // Act
        $sspAssetToCompanyBusinessUnitWritePublisherPlugin = new SspAssetToCompanyBusinessUnitWritePublisherPlugin();
        $sspAssetToCompanyBusinessUnitWritePublisherPlugin->handleBulk(
            [],
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE,
        );

        // Assert
        $sspAssetSearchEntities = SpySspAssetSearchQuery::create()->find();
        $this->assertCount(0, $sspAssetSearchEntities);
    }

    public function testGetSubscribedEventsReturnsCorrectEvents(): void
    {
        // Arrange
        $sspAssetToCompanyBusinessUnitWritePublisherPlugin = new SspAssetToCompanyBusinessUnitWritePublisherPlugin();

        // Act
        $subscribedEvents = $sspAssetToCompanyBusinessUnitWritePublisherPlugin->getSubscribedEvents();

        $expectedEvents = [
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_CREATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_UPDATE,
            SelfServicePortalConfig::ENTITY_SPY_SSP_ASSET_TO_COMPANY_BUSINESS_UNIT_DELETE,
        ];

        // Assert
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
