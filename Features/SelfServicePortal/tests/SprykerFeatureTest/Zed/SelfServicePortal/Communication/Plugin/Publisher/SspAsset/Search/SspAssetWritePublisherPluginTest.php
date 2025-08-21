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
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAsset\Search\SspAssetWritePublisherPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group SspAssetWritePublisherPluginTest
 */
class SspAssetWritePublisherPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_UF = 'UF';

    /**
     * @var string
     */
    protected const STORE_NAME_LO = 'LO';

    protected SelfServicePortalCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $storeFacadeMock = $this->createMock(StoreFacadeInterface::class);
        $storeFacadeMock->method('getAllStores')->willReturn([
            (new StoreTransfer())->setName(static::STORE_NAME_UF),
            (new StoreTransfer())->setName(static::STORE_NAME_LO),
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
            static::STORE_NAME_UF,
        );

        $this->tester->ensureSspAssetToCompanyBusinessUnitTableIsEmpty();
        $this->tester->ensureSspAssetToSspModelTableIsEmpty();
        $this->tester->ensureSspAssetTableIsEmpty();
    }

    public function testHandleBulkWritesSspAssetSearchData(): void
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
            CompanyBusinessUnitTransfer::COMPANY => $companyTransfer2->getIdCompany(),
        ]);

        $sspModelTransfer1 = $this->tester->haveSspModel();
        $sspModelTransfer2 = $this->tester->haveSspModel();

        $sspAssetTransfer1 = $this->tester->haveAsset([
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer1,
            SspAssetTransfer::BUSINESS_UNIT_ASSIGNMENTS => [
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer1],
                [SspAssetBusinessUnitAssignmentTransfer::COMPANY_BUSINESS_UNIT => $businessUnitTransfer2],
            ],
            SspAssetTransfer::SSP_MODELS => [
                $sspModelTransfer1->modifiedToArray(false, true),
                $sspModelTransfer2->modifiedToArray(false, true),
            ],
        ]);
        $sspAssetTransfer2 = $this->tester->haveAsset([
            SspAssetTransfer::SERIAL_NUMBER => '1234567890',
        ]);

        // Act
        $sspAssetWritePublisherPlugin = new SspAssetWritePublisherPlugin();
        $sspAssetWritePublisherPlugin->handleBulk(
            [
                (new EventEntityTransfer())->setId($sspAssetTransfer1->getIdSspAsset()),
                (new EventEntityTransfer())->setId($sspAssetTransfer2->getIdSspAsset()),
            ],
            null,
        );

        // Assert
        $sspAssetSearchEntities = SpySspAssetSearchQuery::create()->filterByFkSspAsset_In([
            $sspAssetTransfer1->getIdSspAsset(),
            $sspAssetTransfer2->getIdSspAsset(),
        ])->orderByFkSspAsset()->find();

        $this->assertCount(2, $sspAssetSearchEntities);

        $sspAssetSearchEntity1 = $sspAssetSearchEntities[0];
        $sspAssetSearchEntity2 = $sspAssetSearchEntities[1];

        $this->assertSame($sspAssetTransfer1->getIdSspAsset(), $sspAssetSearchEntity1->getFkSspAsset());
        $this->assertSame($sspAssetTransfer2->getIdSspAsset(), $sspAssetSearchEntity2->getFkSspAsset());

        $this->assertSspAssetSearchData($sspAssetSearchEntity1->getData(), $sspAssetTransfer1);
        $this->assertSspAssetSearchData($sspAssetSearchEntity2->getData(), $sspAssetTransfer2);
    }

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return void
     */
    protected function assertSspAssetSearchData(array $data, SspAssetTransfer $sspAssetTransfer): void
    {
        $this->assertArrayHasKey(SspAssetIndexMap::TYPE, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::SEARCH_RESULT_DATA, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::FULL_TEXT_BOOSTED, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::SUGGESTION_TERMS, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::COMPLETION_TERMS, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::STORE, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::BUSINESS_UNIT_IDS, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::COMPANY_IDS, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::ID_OWNER_BUSINESS_UNIT, $data);
        $this->assertArrayHasKey(SspAssetIndexMap::ID_OWNER_COMPANY_ID, $data);

        $this->assertSame(SelfServicePortalConfig::SSP_ASSET_RESOURCE_NAME, $data[SspAssetIndexMap::TYPE]);
        $this->assertSame([
            'name' => $sspAssetTransfer->getName(),
            'serial_number' => $sspAssetTransfer->getSerialNumber(),
            'model_ids' => array_map(fn (SspModelTransfer $sspModel) => $sspModel->getIdSspModel(), $sspAssetTransfer->getSspModels()->getArrayCopy()),
            'busines_unit_ids' => array_map(fn (SspAssetBusinessUnitAssignmentTransfer $businessUnitAssignment) => $businessUnitAssignment->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(), $sspAssetTransfer->getBusinessUnitAssignments()->getArrayCopy()),
            'company_ids' => array_map(fn (SspAssetBusinessUnitAssignmentTransfer $businessUnitAssignment) => $businessUnitAssignment->getCompanyBusinessUnitOrFail()->getFkCompanyOrFail(), $sspAssetTransfer->getBusinessUnitAssignments()->getArrayCopy()),
            'id_owner_business_unit' => $sspAssetTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnitOrFail(),
            'id_owner_company_id' => $sspAssetTransfer->getCompanyBusinessUnit()?->getFkCompany(),
        ], $data[SspAssetIndexMap::SEARCH_RESULT_DATA]);

        $this->assertSame([static::STORE_NAME_UF, static::STORE_NAME_LO], $data[SspAssetIndexMap::STORE]);

        $assignedBusinessUnitIds = array_map(
            fn (SspAssetBusinessUnitAssignmentTransfer $businessUnitAssignment) => $businessUnitAssignment->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(),
            $sspAssetTransfer->getBusinessUnitAssignments()->getArrayCopy(),
        );

        $assignedCompanyIds = array_map(
            fn (SspAssetBusinessUnitAssignmentTransfer $businessUnitAssignment) => $businessUnitAssignment->getCompanyBusinessUnitOrFail()->getFkCompany(),
            $sspAssetTransfer->getBusinessUnitAssignments()->getArrayCopy(),
        );

        $this->assertSame($assignedBusinessUnitIds, $data[SspAssetIndexMap::BUSINESS_UNIT_IDS]);
        $this->assertSame($assignedCompanyIds, $data[SspAssetIndexMap::COMPANY_IDS]);
        $this->assertSame($sspAssetTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnitOrFail(), $data[SspAssetIndexMap::ID_OWNER_BUSINESS_UNIT]);
        $this->assertSame($sspAssetTransfer->getCompanyBusinessUnit()?->getFkCompany(), $data[SspAssetIndexMap::ID_OWNER_COMPANY_ID]);

        $sspModelNames = array_map(fn (SspModelTransfer $sspModel) => $sspModel->getName(), $sspAssetTransfer->getSspModels()->getArrayCopy());

        $this->assertSame(array_filter([
            $sspAssetTransfer->getName(),
            ...$sspModelNames,
            $sspAssetTransfer->getSerialNumber(),
        ]), $data[SspAssetIndexMap::FULL_TEXT_BOOSTED]);

        $this->assertSame(array_filter([
            $sspAssetTransfer->getName(),
            ...$sspModelNames,
        ]), $data[SspAssetIndexMap::SUGGESTION_TERMS]);

        $this->assertSame(array_filter([
            $sspAssetTransfer->getName(),
            ...$sspModelNames,
        ]), $data[SspAssetIndexMap::COMPLETION_TERMS]);

        $this->assertSame($assignedBusinessUnitIds, $data[SspAssetIndexMap::BUSINESS_UNIT_IDS]);
        $this->assertSame($assignedCompanyIds, $data[SspAssetIndexMap::COMPANY_IDS]);
        $this->assertSame($sspAssetTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnitOrFail(), $data[SspAssetIndexMap::ID_OWNER_BUSINESS_UNIT]);
        $this->assertSame($sspAssetTransfer->getCompanyBusinessUnit()?->getFkCompany(), $data[SspAssetIndexMap::ID_OWNER_COMPANY_ID]);
    }
}
