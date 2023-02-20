<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp;

use Codeception\Test\Unit;
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
}
