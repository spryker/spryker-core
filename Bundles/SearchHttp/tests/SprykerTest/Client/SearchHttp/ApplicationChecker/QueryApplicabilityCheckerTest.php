<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\ApplicationChecker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Shared\SearchHttp\SearchHttpConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group SearchHttp
 * @group ApplicationChecker
 * @group QueryApplicabilityCheckerTest
 * Add your own group annotations below this line
 */
class QueryApplicabilityCheckerTest extends Unit
{
    /**
     * @var \SprykerTest\Client\SearchHttp\SearchHttpClientTester
     */
    protected $tester;

    /**
     * When source identifiers are not specified in config, the query is applicable for all types.
     *
     * @return void
     */
    public function testQueryApplicabilitySuccessfullyCheckedWhenQueryIsApplicable(): void
    {
        // Arrange
        $this->tester->mockStoreClientDependency();
        $this->tester->mockSynchronizationServiceDependency();
        $this->tester->mockStorageClientDependency('{"search_http_configs":[{"application_id":"app_id","url":"url"}]}');
        $queryApplicabilityChecker = $this->tester->getFactory()->createQueryApplicabilityChecker();

        // Act
        $isQueryApplicable = $queryApplicabilityChecker->isQueryApplicable(new SearchContextTransfer());

        // Assert
        $this->assertTrue($isQueryApplicable);
    }

    /**
     * @return void
     */
    public function testQueryApplicabilitySuccessfullyCheckedWhenProductQueryIsApplicable(): void
    {
        // Arrange
        $this->tester->mockStoreClientDependency();
        $this->tester->mockSynchronizationServiceDependency();
        $this->tester->mockStorageClientDependency('{"search_http_configs":[{"application_id":"app_id","url":"url","settings":{"source_identifiers":["product"]}}]}');
        $queryApplicabilityChecker = $this->tester->getFactory()->createQueryApplicabilityChecker();

        // Act
        $isQueryApplicable = $queryApplicabilityChecker->isQueryApplicable(
            (new SearchContextTransfer())->setSourceIdentifier(SearchHttpConfig::SOURCE_IDENTIFIER_PRODUCT),
        );

        // Assert
        $this->assertTrue($isQueryApplicable);
    }

    /**
     * @return void
     */
    public function testQueryApplicabilitySuccessfullyCheckedWhenProductQueryIsNotApplicable(): void
    {
        // Arrange
        $this->tester->mockStoreClientDependency();
        $this->tester->mockSynchronizationServiceDependency();
        $this->tester->mockStorageClientDependency('{"search_http_configs":[{"application_id":"app_id","url":"url","settings":{"source_identifiers":["not-product"]}}]}');
        $queryApplicabilityChecker = $this->tester->getFactory()->createQueryApplicabilityChecker();

        // Act
        $isQueryApplicable = $queryApplicabilityChecker->isQueryApplicable(
            (new SearchContextTransfer())->setSourceIdentifier(SearchHttpConfig::SOURCE_IDENTIFIER_PRODUCT),
        );

        // Assert
        $this->assertFalse($isQueryApplicable);
    }

    /**
     * @return void
     */
    public function testQueryApplicabilitySuccessfullyCheckedWhenQueryIsNotApplicable(): void
    {
        // Arrange
        $this->tester->mockStoreClientDependency();
        $this->tester->mockSynchronizationServiceDependency();
        $this->tester->mockStorageClientDependency('{"search_http_configs":[]}');
        $queryApplicabilityChecker = $this->tester->getFactory()->createQueryApplicabilityChecker();

        // Act
        $isQueryApplicable = $queryApplicabilityChecker->isQueryApplicable(new SearchContextTransfer());

        // Assert
        $this->assertFalse($isQueryApplicable);
    }
}
