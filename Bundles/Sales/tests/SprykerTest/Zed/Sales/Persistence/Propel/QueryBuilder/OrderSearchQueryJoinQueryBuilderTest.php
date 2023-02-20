<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Persistence\Propel\QueryBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Generated\Shared\Transfer\QueryJoinTransfer;
use Generated\Shared\Transfer\QueryWhereConditionTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchQueryJoinQueryBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Persistence
 * @group Propel
 * @group QueryBuilder
 * @group OrderSearchQueryJoinQueryBuilderTest
 * Add your own group annotations below this line
 */
class OrderSearchQueryJoinQueryBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_FULL_NAME = 'name';

    /**
     * @var string
     */
    protected const TEST_ORDER_REFERENCE = 'orderReference';

    /**
     * @var string
     */
    protected const COLUMN_FULL_NAME = "CONCAT(first_name, ' ', last_name)";

    /**
     * @return void
     */
    public function testAddSalesOrderQueryFiltersShouldBindFilterValues(): void
    {
        // Arrange
        $queryJoinTransferConcat = (new QueryJoinTransfer())->addQueryWhereCondition(
            (new QueryWhereConditionTransfer())
                ->setColumn(static::COLUMN_FULL_NAME)
                ->setValue(static::TEST_FULL_NAME),
        );

        $queryJoinTransfer = (new QueryJoinTransfer())->addQueryWhereCondition(
            (new QueryWhereConditionTransfer())
                ->setColumn(SpySalesOrderTableMap::COL_ORDER_REFERENCE)
                ->setValue(static::TEST_ORDER_REFERENCE),
        );

        $queryJoinCollectionTransfer = (new QueryJoinCollectionTransfer())
            ->addQueryJoin($queryJoinTransferConcat)
            ->addQueryJoin($queryJoinTransfer);

        $expectedQuery = sprintf(
            '(%s LIKE :p1 AND %s LIKE :p2)',
            static::COLUMN_FULL_NAME,
            SpySalesOrderTableMap::COL_ORDER_REFERENCE,
        );

        // Act
        $spySalesOrderQuery = (new OrderSearchQueryJoinQueryBuilder())
            ->addSalesOrderQueryFilters(
                new SpySalesOrderQuery(),
                $queryJoinCollectionTransfer,
            );

        // Assert
        $this->assertSame($expectedQuery, trim((string)$spySalesOrderQuery));
    }
}
