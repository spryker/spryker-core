<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConcretePageSearchBuilder;
use Generated\Shared\DataBuilder\SuggestionsSearchHttpResponseBuilder;
use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\SearchHttp\Api\SearchHttpApiInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group SearchHttpClientTest
 * Add your own group annotations below this line
 */
class SearchHttpClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SearchHttp\SearchHttpClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSearchSuccessfullyReturnSearchResult(): void
    {
        // Arrange
        $this->tester->mockLocaleClientDependency();
        $searchApiClient = $this->mockSearchApiClient();
        $searchQuery = $this->tester->getSearchHttpQueryPlugin();

        // Assert
        $searchApiClient
            ->expects($this->exactly(1))
            ->method('search')
            ->with($searchQuery, [], [])
            ->willReturn([]);

        // Act
        $this->tester->getClient()->search($searchQuery);
    }

    /**
     * @return \Spryker\Client\SearchHttp\Api\SearchHttpApiInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function mockSearchApiClient(): SearchHttpApiInterface|MockObject
    {
        $searchApiClient = $this->makeEmpty(SearchHttpApiInterface::class);

        $this->tester->mockFactoryMethod(
            'createSearchApiClient',
            $searchApiClient,
        );

        return $searchApiClient;
    }

    /**
     * @return void
     */
    public function testFormatProductConcreteCatalogHttpSearchResultFormatsTheGivenDataSuccessfully(): void
    {
        // Arrange
        $productAbstractSku = 'abstract-sku';
        $productConcretePageSearchTransfer = (new ProductConcretePageSearchBuilder())->build();

        $suggestionsSearchHttpResponseTransfer = (new SuggestionsSearchHttpResponseBuilder([
            SuggestionsSearchHttpResponseTransfer::MATCHED_ITEMS => [
                [
                    'product_abstract_sku' => $productAbstractSku,
                ] + $productConcretePageSearchTransfer->toArray(),
            ],
        ]))->build();

        $productConcretePageSearchTransfer->setAbstractSku($productAbstractSku);

        // Act
        $formattedSearchResults = $this->tester->getClient()
            ->formatProductConcreteCatalogHttpSearchResult($suggestionsSearchHttpResponseTransfer);

        // Assert
        $this->assertSame($productConcretePageSearchTransfer->toArray(), $formattedSearchResults[0]->toArray());
    }

    /**
     * @return void
     */
    public function testFindSearchResultTotalCountReturnsCountOnCorrectSearchHttpResult(): void
    {
        // Arrange
        $totalCount = 10;
        $searchResult = [
            'pagination' => [
                'num_found' => $totalCount,
            ],
        ];

        // Act
        $foundTotalCount = $this->tester->getClient()
            ->findSearchResultTotalCount($searchResult);

        // Assert
        $this->assertSame($totalCount, $foundTotalCount);
    }

    /**
     * @dataProvider incorrectSearchResultDataProvider
     *
     * @param mixed $searchResult
     *
     * @return void
     */
    public function testFindSearchResultTotalCountReturnsNullOnIncorrectSearchHttpResult($searchResult): void
    {
        // Act
        $foundTotalCount = $this->tester->getClient()
            ->findSearchResultTotalCount($searchResult);

        // Assert
        $this->assertNull($foundTotalCount);
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function incorrectSearchResultDataProvider(): array
    {
        return [
            'empty array' => [[]],
            'object' => [(object)[]],
            'incorrect array' => [['pagination' => []]],
            'object with pagination' => [(object)['pagination' => ['num_found' => 1]]],
        ];
    }
}
