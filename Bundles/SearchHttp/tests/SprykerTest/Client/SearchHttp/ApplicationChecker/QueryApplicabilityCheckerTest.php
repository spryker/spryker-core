<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\SearchHttp\ApplicationChecker;

use Codeception\Test\Unit;

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
     * @return void
     */
    public function testQueryApplicabilitySuccessfullyCheckedWhenQueryIsApplicable(): void
    {
        // Arrange
        $this->tester->mockStoreClientDependency();
        $this->tester->mockStorageClientDependency('{"search_http_configs":[{"application_id":"app_id","url":"url"}]}');
        $queryApplicabilityChecker = $this->tester->getFactory()->createQueryApplicabilityChecker();

        // Act
        $isQueryApplicable = $queryApplicabilityChecker->isQueryApplicable();

        // Assert
        $this->assertTrue($isQueryApplicable);
    }

    /**
     * @return void
     */
    public function testQueryApplicabilitySuccessfullyCheckedWhenQueryIsNotApplicable(): void
    {
        // Arrange
        $this->tester->mockStoreClientDependency();
        $this->tester->mockStorageClientDependency('{"search_http_configs":[]}');
        $queryApplicabilityChecker = $this->tester->getFactory()->createQueryApplicabilityChecker();

        // Act
        $isQueryApplicable = $queryApplicabilityChecker->isQueryApplicable();

        // Assert
        $this->assertFalse($isQueryApplicable);
    }
}
