<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Client\SearchHttp;

use ArrayObject;
use Codeception\Actor;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Generated\Shared\DataBuilder\CategoryNodeStorageBuilder;
use Generated\Shared\DataBuilder\MoneyBuilder;
use Generated\Shared\DataBuilder\SearchHttpResponseBuilder;
use Generated\Shared\DataBuilder\SearchHttpResponsePaginationBuilder;
use Generated\Shared\DataBuilder\SearchQueryPaginationBuilder;
use Generated\Shared\DataBuilder\SearchQueryRangeFacetFilterBuilder;
use Generated\Shared\DataBuilder\SearchQuerySortingBuilder;
use Generated\Shared\DataBuilder\SearchQueryValueFacetFilterBuilder;
use Generated\Shared\DataBuilder\SuggestionsSearchHttpResponseBuilder;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\PaginationConfigTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchHttpResponseTransfer;
use Generated\Shared\Transfer\SearchHttpSearchContextTransfer;
use Generated\Shared\Transfer\SortConfigTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Stream\Stream;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchHttp\AggregationExtractor\AggregationExtractorFactory;
use Spryker\Client\SearchHttp\Config\FacetConfig;
use Spryker\Client\SearchHttp\Config\PaginationConfig;
use Spryker\Client\SearchHttp\Config\SearchConfigInterface;
use Spryker\Client\SearchHttp\Config\SortConfig;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCategoryStorageClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToLocaleClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToMoneyClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToProductStorageClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStorageClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface;
use Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToUtilEncodingServiceInterface;
use Spryker\Client\SearchHttp\Mapper\ResultProductMapper;
use Spryker\Client\SearchHttp\Plugin\Catalog\Query\SearchHttpQueryPlugin;
use Spryker\Client\SearchHttp\SearchHttpDependencyProvider;
use Spryker\Shared\SearchHttp\SearchHttpConfig;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Client\SearchHttp\PHPMD)
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 * @method \Spryker\Client\SearchHttp\SearchHttpConfig getModuleConfig()
 * @method \Spryker\Client\SearchHttp\SearchHttpClientInterface getClient()
 */
class SearchHttpClientTester extends Actor
{
    use _generated\SearchHttpClientTesterActions;

    /**
     * @var string
     */
    protected const STORE_NAME = 'store';

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function getSearchHttpQueryPlugin(): QueryInterface
    {
        return new SearchHttpQueryPlugin(
            (new SearchContextTransfer())
                ->setSourceIdentifier(SearchHttpConfig::SOURCE_IDENTIFIER_PRODUCT)
                ->setSearchHttpContext(new SearchHttpSearchContextTransfer()),
        );
    }

    /**
     * @return void
     */
    public function mockLocaleClientDependency(): void
    {
        $localeClient = Stub::makeEmpty(SearchHttpToLocaleClientInterface::class);
        $localeClient->method('getCurrentLocale')->willReturn('de_DE');

        $this->mockFactoryMethod('getLocaleClient', $localeClient);
        $this->setDependency(SearchHttpDependencyProvider::CLIENT_LOCALE, $localeClient);
    }

    /**
     * @return void
     */
    public function mockStoreClientDependency(): void
    {
        $storeClient = Stub::makeEmpty(SearchHttpToStoreClientInterface::class);
        $storeClient
            ->method('getCurrentStore')
            ->willReturn(
                (new StoreTransfer())
                    ->setName(static::STORE_NAME)
                    ->setStoreReference('store-reference'),
            );
        $storeClient->method('isCurrentStoreDefined')
            ->willReturn(true);

        $this->mockFactoryMethod('getStoreClient', $storeClient);
        $this->setDependency(SearchHttpDependencyProvider::CLIENT_STORE, $storeClient);
    }

    /**
     * @return void
     */
    public function mockMoneyClientDependency(): void
    {
        $moneyClient = Stub::makeEmpty(SearchHttpToMoneyClientInterface::class, [
            'fromFloat' => function ($price) {
                $money = (new MoneyBuilder())->build();
                $money->setAmount(($price * 100));

                return $money;
            },
        ]);

        $this->mockFactoryMethod('getMoneyClient', $moneyClient);
        $this->setDependency(SearchHttpDependencyProvider::CLIENT_MONEY, $moneyClient);
    }

    /**
     * @param array $skusToProductAbstractIds
     *
     * @return void
     */
    public function mockProductStorageClientDependency(array $skusToProductAbstractIds = []): void
    {
        $productStorageClient = Stub::makeEmpty(SearchHttpToProductStorageClientInterface::class);
        $productStorageClient->method('getBulkProductAbstractIdsByMapping')->willReturn($skusToProductAbstractIds);

        $this->mockFactoryMethod('getProductStorageClient', $productStorageClient);
        $this->setDependency(SearchHttpDependencyProvider::CLIENT_PRODUCT_STORAGE, $productStorageClient);
    }

    /**
     * @param string $searchHttpConfigJson
     *
     * @return void
     */
    public function mockStorageClientDependency(string $searchHttpConfigJson): void
    {
        $storageClient = Stub::makeEmpty(SearchHttpToStorageClientInterface::class);
        $storageClient->method('get')->willReturn(json_decode($searchHttpConfigJson, true));

        $this->mockFactoryMethod('getStorageClient', $storageClient);
        $this->setDependency(SearchHttpDependencyProvider::CLIENT_STORE, $storageClient);
    }

    /**
     * @return void
     */
    public function mockUtilEncodingServiceDependency(): void
    {
        $utilEncodingService = Stub::makeEmpty(SearchHttpToUtilEncodingServiceInterface::class);
        $utilEncodingService->method('decodeJson')->willReturnCallback(function ($jsonValue) {
            return json_decode($jsonValue, true);
        });

        $this->mockFactoryMethod('getUtilEncodingService', $utilEncodingService);
        $this->setDependency(SearchHttpDependencyProvider::SERVICE_UTIL_ENCODING, $utilEncodingService);
    }

    /**
     * @return void
     */
    public function mockAggregationExtractorFactory(): void
    {
        $this->mockFactoryMethod(
            'createAggregationExtractorFactory',
            new AggregationExtractorFactory(
                $this->getFactory()->getMoneyClient(),
                $this->getFactory()->getCategoryStorageClient(),
                $this->getFactory()->getLocaleClient(),
                $this->getFactory()->getStoreClient(),
            ),
        );
    }

    /**
     * @return void
     */
    public function addResultProductMapperToMockedFactory(): void
    {
        $this->mockFactoryMethod('createResultProductMapper', new ResultProductMapper());
    }

    /**
     * @return void
     */
    public function mockCategoryStorageClientDependency(): void
    {
        $categoryStorageClient = Stub::makeEmpty(SearchHttpToCategoryStorageClientInterface::class);

        $categoryNodeStorageTransfers = new ArrayObject(
            [
                (new CategoryNodeStorageBuilder())->build()
                ->setNodeId(1)
                ->setIdCategory(1)
                ->setName('Category_1')
                ->setChildren(new ArrayObject([
                    (new CategoryNodeStorageBuilder())->build()
                        ->setNodeId(2)
                        ->setIdCategory(2)
                        ->setName('Category_2'),
                    (new CategoryNodeStorageBuilder())->build()->setIdCategory(4),
                    (new CategoryNodeStorageBuilder())->build()->setIdCategory(5),
                ])),
                (new CategoryNodeStorageBuilder())
                    ->build()
                    ->setNodeId(3)
                    ->setIdCategory(3)
                    ->setName('Category_3'),
            ],
        );

        $categoryStorageClient->method('getCategories')->willReturn($categoryNodeStorageTransfers);
        $categoryStorageClient
            ->method('getCategoryNodeById')
            ->willReturn((new CategoryNodeStorageBuilder())
                ->build()
                ->setNodeId(3)
                ->setName('Category_3'));

        $this->mockFactoryMethod('getCategoryStorageClient', $categoryStorageClient);
        $this->setDependency(SearchHttpDependencyProvider::CLIENT_CATEGORY_STORAGE, $categoryStorageClient);
    }

    /**
     * @return void
     */
    public function mockSearchConfig(): void
    {
        $searchConfigMock = Stub::makeEmpty(SearchConfigInterface::class);

        $facetConfig = new FacetConfig();
        $facetConfig->addFacet(
            (new FacetConfigTransfer())
                ->setName('range')
                ->setParameterName('range')
                ->setFieldName('field-name')
                ->setType(SearchHttpConfig::FACET_TYPE_RANGE),
        );
        $facetConfig->addFacet(
            (new FacetConfigTransfer())
                ->setName('price')
                ->setParameterName('price')
                ->setFieldName('field-name')
                ->setType(SearchHttpConfig::FACET_TYPE_PRICE_RANGE),
        );
        $facetConfig->addFacet(
            (new FacetConfigTransfer())
                ->setName('category')
                ->setParameterName('category')
                ->setFieldName('field-name')
                ->setType(SearchHttpConfig::FACET_TYPE_CATEGORY),
        );

        $searchConfigMock
            ->method('getFacetConfig')
            ->willReturn($facetConfig);

        $sortConfig = new SortConfig();
        $sortConfig->addSort(
            (new SortConfigTransfer())
                ->setParameterName('foo')
                ->setFieldName('field-name')
                ->setIsDescending(false)
                ->setName('foo'),
        );

        $searchConfigMock
            ->method('getSortConfig')
            ->willReturn($sortConfig);

        $paginationConfig = new PaginationConfig();
        $paginationConfig->setPagination(
            (new PaginationConfigTransfer())
                ->setParameterName('page')
                ->setItemsPerPageParameterName('ipp')
                ->setDefaultItemsPerPage(10)
                ->setMaxItemsPerPage(500)
                ->setValidItemsPerPageOptions([10, 500]),
        );

        $searchConfigMock
            ->method('getPaginationConfig')
            ->willReturn($paginationConfig);

        $this->mockFactoryMethod('getSearchConfig', $searchConfigMock);
    }

    /**
     * @return \Generated\Shared\Transfer\SearchHttpResponseTransfer
     */
    public function createSearchHttpResponse(): SearchHttpResponseTransfer
    {
        return (new SearchHttpResponseBuilder())
            ->build()
            ->setFacets(
                [
                    'category' => [
                        'Category_1' => 1,
                        'Category_2' => 10,
                        'Category_3' => 100,
                    ],
                    'price' => [
                        'from' => 1000,
                        'to' => 10000,
                    ],
                    'range' => [
                        'from' => 200,
                        'to' => 2000,
                    ],
                    'custom_configured_range_facet' => [
                        'from' => 1,
                        'to' => 5,
                    ],
                    'custom_configured_values_facet' => [
                        'value1' => 5,
                        'value2' => 15,
                        'value3' => 20,
                    ],
                ],
            )
            ->setPagination((new SearchHttpResponsePaginationBuilder())->build())
            ->setItems(
                [
                    [
                        'sku' => 'product-sku',
                        'product_abstract_sku' => 'product-abstract-sku',
                        'name' => 'product-name',
                        'abstract_name' => 'product-abstract-name',
                        'description' => 'product-description',
                        'images' => [
                            'default' => [
                                0 => [
                                    'small' => 'product-image-1',
                                    'large' => 'product-image-2',
                                ],
                            ],
                        ],
                        'label' => ['product-label-1', 'product-label-2'],
                        'merchant_name' => ['product-merchant-1', 'product-merchant-2'],
                        'merchant_reference' => ['merchant-reference-1', 'merchant-reference-2'],
                        'keywords' => 'keyword-1,keyword-2',
                        'url' => '/product-url',
                        'rating' => 3,
                        'categories' => ['category-1', 'category-2'],
                        'attributes' => [
                            [
                                'name' => 'attribute-name-1',
                                'value' => 'attribute-value-1',
                            ],
                        ],
                        'prices' => [
                            [
                                'currency' => 'EUR',
                                'price_gross' => 10,
                                'price_net' => 9,
                            ],
                        ],
                    ],
                ],
            );
    }

    /**
     * @return \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer
     */
    public function createSuggestionsSearchHttpResponse(): SuggestionsSearchHttpResponseTransfer
    {
        return (new SuggestionsSearchHttpResponseBuilder())
            ->build()
            ->setMatches([
                'name' => ['suggestion-product-sku'],
                'abstract_name' => ['suggestion-product-sku'],
                'category' => ['category-1'],
            ])
            ->setMatchedItems(
                [
                    [
                        'sku' => 'suggestion-product-sku',
                        'product_abstract_sku' => 'suggestion-product-abstract-sku',
                        'name' => 'suggestion-product-name',
                        'abstract_name' => 'suggestion-product-abstract-name',
                        'description' => 'suggestion-product-description',
                        'images' => [
                            'default' => [
                                0 => [
                                    'small' => 'product-image-1',
                                    'large' => 'product-image-2',
                                ],
                            ],
                        ],
                        'label' => ['product-label-1', 'product-label-2'],
                        'merchant_name' => ['product-merchant-1', 'product-merchant-2'],
                        'merchant_reference' => ['merchant-reference-1', 'merchant-reference-2'],
                        'keywords' => 'keyword-1,keyword-2',
                        'url' => '/product-url',
                        'rating' => 3,
                        'categories' => ['category-1', 'category-2'],
                        'attributes' => [
                            [
                                'name' => 'attribute-name-1',
                                'value' => 'attribute-value-1',
                            ],
                        ],
                        'prices' => [
                            [
                                'currency' => 'EUR',
                                'price_gross' => 10,
                                'price_net' => 9,
                            ],
                        ],
                    ],
                ],
            )
            ->setCompletions(['suggestion-product-sku'])
            ->setCategories(['category-1']);
    }

    /**
     * @param \GuzzleHttp\Psr7\Request $httpRequest
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $responseData
     *
     * @return void
     */
    public function mockHttpClient(Request $httpRequest, QueryInterface $searchQuery, array $responseData): void
    {
        $httpClientMock = Stub::makeEmpty(ClientInterface::class);

        $handler = fopen('data://text/plain,' . json_encode($responseData), 'r');
        $stream = new Stream($handler);

        $response = new Response(200, [], $stream);

        $httpClientMock
            ->expects(Expected::once()->getMatcher())
            ->method('send')
            ->with(
                $httpRequest,
                [
                    'query' => [
                        'query' => $searchQuery->getSearchQuery()->getQueryString(),
                        'facets' => [
                            $searchQuery->getSearchQuery()->getSearchQueryFacetFilters()[0]->getFieldName() => [
                                'type' => 'values',
                                'values' => $searchQuery->getSearchQuery()->getSearchQueryFacetFilters()[0]->getValues(),
                            ],
                            $searchQuery->getSearchQuery()->getSearchQueryFacetFilters()[1]->getFieldName() => [
                                'type' => 'range',
                                'values' => [
                                    'from' => $searchQuery->getSearchQuery()->getSearchQueryFacetFilters()[1]->getFrom(),
                                    'to' => $searchQuery->getSearchQuery()->getSearchQueryFacetFilters()[1]->getTo(),
                                ],
                            ],
                        ],
                        'sorting' => [
                            'field' => $searchQuery->getSearchQuery()->getSort()->getFieldName(),
                            'direction' => $searchQuery->getSearchQuery()->getSort()->getSortDirection(),
                        ],
                        'pagination' => [
                            'page' => $searchQuery->getSearchQuery()->getPagination()->getPage(),
                            'hitsPerPage' => $searchQuery->getSearchQuery()->getPagination()->getItemsPerPage(),
                        ],
                        'store' => static::STORE_NAME,
                    ],
                ],
            )
            ->willReturn($response);

        $this->mockFactoryMethod('createHttpClient', $httpClientMock);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQueryPlugin
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function extendWithTestData(QueryInterface $searchQueryPlugin): QueryInterface
    {
        $searchQueryPlugin->getSearchQuery()
            ->setQueryString('search-string')
            ->setLocale('de_DE')
            ->setPagination(
                (new SearchQueryPaginationBuilder())->build(),
            )
            ->setSort(
                (new SearchQuerySortingBuilder())->build(),
            )
            ->setSearchQueryFacetFilters(
                [
                    (new SearchQueryValueFacetFilterBuilder())
                        ->build()
                        ->setValues(
                            [
                                'value1' => 'data1',
                                'value2' => 'data2',
                            ],
                        ),
                    (new SearchQueryRangeFacetFilterBuilder())->build(),
                ],
            );

        return $searchQueryPlugin;
    }
}
