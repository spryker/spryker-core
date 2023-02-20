<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\Api;

use Codeception\Test\Unit;
use GuzzleHttp\Psr7\Request;
use Spryker\Shared\SearchHttp\SearchHttpConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group Api
 * @group SearchHttpApiClientTest
 * Add your own group annotations below this line
 */
class SearchHttpApiClientTest extends Unit
{
    /**
     * @var string
     */
    protected const SEARCH_HTTP_CONFIG_DATA = '{"search_http_configs":[{"application_id":"app_id","url":"url"}]}';

    /**
     * @var array<string, string>
     */
    protected const REQUEST_HEADERS = [
        'X-Store-Reference' => 'store-reference',
        'Accept-Language' => 'de_DE',
    ];

    /**
     * @var \SprykerTest\Client\SearchHttp\SearchHttpClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSearchHttpRequestSuccessfullySent(): void
    {
        // Arrange
        $searchQuery = $this->tester->getSearchHttpQueryPlugin();
        $searchQuery = $this->tester->extendWithTestData($searchQuery);
        $this->tester->mockStoreClientDependency();
        $this->tester->mockStorageClientDependency(static::SEARCH_HTTP_CONFIG_DATA);

        $httpRequest = new Request(
            SearchHttpConfig::SEARCH_HTTP_METHOD,
            'url',
            static::REQUEST_HEADERS,
        );
        $responseData = [['responseData']];

        $this->tester->mockHttpClient($httpRequest, $searchQuery, $responseData);

        $searchApiClient = $this->tester->getFactory()->createSearchApiClient();

        // Act
        $response = $searchApiClient->search($searchQuery);

        // Assert
        $this->assertEquals($responseData[0], $response);
    }

    /**
     * @return void
     */
    public function testSearchHttpRequestReturnsEmptyArrayOnException(): void
    {
        // Arrange
        $searchQuery = $this->tester->getSearchHttpQueryPlugin();
        $searchQuery = $this->tester->extendWithTestData($searchQuery);
        $this->tester->mockStoreClientDependency();
        $this->tester->mockStorageClientDependency(static::SEARCH_HTTP_CONFIG_DATA);

        $httpRequest = new Request(
            SearchHttpConfig::SEARCH_HTTP_METHOD,
            'url',
            static::REQUEST_HEADERS,
        );
        $responseData = ['wrong_response'];

        $this->tester->mockHttpClient($httpRequest, $searchQuery, $responseData);

        $searchApiClient = $this->tester->getFactory()->createSearchApiClient();

        // Act
        $response = $searchApiClient->search($searchQuery);

        // Assert
        $this->assertEquals([], $response);
    }
}
