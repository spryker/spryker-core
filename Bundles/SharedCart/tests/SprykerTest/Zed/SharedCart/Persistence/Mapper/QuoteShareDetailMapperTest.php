<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart\Persistence\Mapper;

use Codeception\Test\Unit;
use Spryker\Zed\SharedCart\Persistence\Propel\Mapper\QuoteShareDetailMapper;
use SprykerTest\Zed\SharedCart\SharedCartPersistenceTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SharedCart
 * @group Persistence
 * @group Mapper
 * @group QuoteShareDetailMapperTest
 * Add your own group annotations below this line
 */
class QuoteShareDetailMapperTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SharedCart\SharedCartPersistenceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapShareDetailCollectionByQuoteIdReturnsListOfShareDetailsOrderedById()
    {
        // Arrange
        $quoteCompanyUserEntities = $this->tester->getQuoteCompanyUserCollection();
        $quotePermissionGroupTransfers = $this->tester->getQuotePermissionGroupTransferCollection();

        // Act
        $quoteShareDetailMapper = new QuoteShareDetailMapper();
        $shareDetailTransferCollection = $quoteShareDetailMapper->mapShareDetailCollectionByQuoteId(
            $quoteCompanyUserEntities,
            $quotePermissionGroupTransfers
        );

        // Assert
        $this->assertArrayHasKey(SharedCartPersistenceTester::MAPPING_KEY, $shareDetailTransferCollection);
    }
}
