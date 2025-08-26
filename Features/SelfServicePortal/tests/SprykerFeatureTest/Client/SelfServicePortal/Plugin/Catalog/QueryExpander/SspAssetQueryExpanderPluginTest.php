<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Client\SelfServicePortal\Plugin\Catalog\QueryExpander;

use Codeception\Test\Unit;
use Elastica\Query\BoolQuery;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\Storage\StorageClientInterface;
use SprykerFeature\Client\SelfServicePortal\Plugin\Catalog\SspAssetQueryExpanderPlugin;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalDependencyProvider;
use SprykerFeatureTest\Client\SelfServicePortal\SelfServicePortalClientTester;
use SprykerTest\Client\SearchElasticsearch\Plugin\Fixtures\BaseQueryPlugin;
use stdClass;

/**
 * @group SprykerFeatureTest
 * @group Client
 * @group SelfServicePortal
 * @group Plugin
 * @group Catalog
 * @group SspAssetQueryExpanderPluginTest
 */
class SspAssetQueryExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Client\SelfServicePortal\SelfServicePortalClientTester
     */
    protected SelfServicePortalClientTester $tester;

    protected function setUp(): void
    {
        parent::setUp();
        $companyUserClientMock = $this->createMock(CompanyUserClientInterface::class);
        $companyUserClientMock->method('findCompanyUser')->willReturn((new CompanyUserTransfer())->setIdCompanyUser(1));
        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_COMPANY_USER, $companyUserClientMock);
    }

    public function testExpandQueryAddsWhitelistTermsFilterWhenAssetReferenceProvided(): void
    {
        // Arrange
        $storageClientMock = $this->createMock(StorageClientInterface::class);
        $storageClientMock
            ->method('getMulti')
            ->willReturnOnConsecutiveCalls(
                [json_encode([
                    'reference' => 'AST--TEST1',
                    'model_ids' => [111, 222],
                ])],
                [
                    json_encode([
                        'id_ssp_model' => 111,
                        'whitelist_ids' => [1123, 1124],

                    ]),
                    json_encode([
                        'id_ssp_model' => 222,
                        'whitelist_ids' => [2222],
                    ]),
                ],
            );

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $searchQueryPlugin = new BaseQueryPlugin();

        $plugin = new SspAssetQueryExpanderPlugin();

        // Act
        $expanded = $plugin->expandQuery($searchQueryPlugin, ['ssp-asset-reference' => 'AST--TEST1']);

        // Assert
        $boolQuery = $expanded->getSearchQuery()->getQuery();
        $this->assertInstanceOf(BoolQuery::class, $boolQuery);

        $filters = $boolQuery->toArray()['bool']['filter'] ?? [];
        $this->assertNotEmpty($filters, 'Expected at least one filter to be added');

        $termsFilters = array_filter($filters, static function (array $filter): bool {
            return isset($filter['terms']);
        });

        $this->assertNotEmpty($termsFilters, 'Expected a terms filter to be added');
        $termsFilter = array_shift($termsFilters);
        $this->assertArrayHasKey(PageIndexMap::PRODUCT_LISTS_WHITELISTS, $termsFilter['terms']);
        $this->assertSame([1123, 1124, 2222], $termsFilter['terms'][PageIndexMap::PRODUCT_LISTS_WHITELISTS]);
    }

    public function testExpandQueryDoesNotAddFilterWhenAssetIsNotFoundInStorage(): void
    {
        // Arrange
        $sspAssetReference = 'ASSET-NOT-FOUND';

        $storageClientMock = $this->createMock(StorageClientInterface::class);
        $storageClientMock
            ->method('getMulti')
            ->willReturn([]);

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $searchQueryPlugin = new BaseQueryPlugin();

        $plugin = new SspAssetQueryExpanderPlugin();

        // Act
        $expanded = $plugin->expandQuery($searchQueryPlugin, ['ssp-asset-reference' => $sspAssetReference]);

        // Assert
        $query = $expanded->getSearchQuery()->getQuery();
        $this->assertInstanceOf(BoolQuery::class, $query);

        $queryArray = $query->toArray();

        $this->assertArrayHasKey('bool', $queryArray, 'Expected bool clause when asset is not found');
        $this->assertArrayHasKey('must_not', $queryArray['bool'], 'Expected must_not clause when asset is not found');

        $firstMustNot = $queryArray['bool']['must_not'][0];
        $this->assertInstanceOf(stdClass::class, $firstMustNot['match_all']);
    }

    public function testExpandQueryDoesNotAddFilterWhenModelIsNotFoundInStorage(): void
    {
        // Arrange
        $sspAssetReference = 'ASSET-123';
        $modelId = 111;

        $storageClientMock = $this->createMock(StorageClientInterface::class);
        $storageClientMock
            ->method('getMulti')
            ->willReturnOnConsecutiveCalls(
                [json_encode([
                    'reference' => $sspAssetReference,
                    'model_ids' => [$modelId],
                ])],
                [],
            );

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $searchQueryPlugin = new BaseQueryPlugin();

        $plugin = new SspAssetQueryExpanderPlugin();

        // Act
        $expanded = $plugin->expandQuery($searchQueryPlugin, ['ssp-asset-reference' => $sspAssetReference]);

        // Assert
        $query = $expanded->getSearchQuery()->getQuery();
        $this->assertInstanceOf(BoolQuery::class, $query);

        $queryArray = $query->toArray();

        $this->assertArrayHasKey('bool', $queryArray, 'Expected bool clause when asset is not found');
        $this->assertArrayHasKey('must_not', $queryArray['bool'], 'Expected must_not clause when asset is not found');

        $firstMustNot = $queryArray['bool']['must_not'][0];
        $this->assertInstanceOf(stdClass::class, $firstMustNot['match_all']);
    }

    public function testExpandQueryDoesNotAddFilterWhenModelHasNoProductLists(): void
    {
        // Arrange
        $sspAssetReference = 'ASSET-123';
        $modelId = 111;

        $storageClientMock = $this->createMock(StorageClientInterface::class);
        $storageClientMock
            ->method('getMulti')
            ->willReturnOnConsecutiveCalls(
                [json_encode([
                    'reference' => $sspAssetReference,
                    'model_ids' => [$modelId],
                ])],
                [json_encode([
                    'id_model' => $modelId,
                    'whitelist_ids' => [],
                ])],
            );

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $searchQueryPlugin = new BaseQueryPlugin();

        $plugin = new SspAssetQueryExpanderPlugin();

        // Act
        $expanded = $plugin->expandQuery($searchQueryPlugin, ['ssp-asset-reference' => $sspAssetReference]);

        // Assert
        $query = $expanded->getSearchQuery()->getQuery();
        $this->assertInstanceOf(BoolQuery::class, $query);

        $queryArray = $query->toArray();

        $this->assertArrayHasKey('bool', $queryArray, 'Expected bool clause when asset is not found');
        $this->assertArrayHasKey('must_not', $queryArray['bool'], 'Expected must_not clause when asset is not found');

        $firstMustNot = $queryArray['bool']['must_not'][0];
        $this->assertInstanceOf(stdClass::class, $firstMustNot['match_all']);
    }
}
