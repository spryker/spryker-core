<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Propel\PropelFilterCriteria;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelFilterCriteriaTest
 * Add your own group annotations below this line
 */
class PropelFilterCriteriaTest extends Unit
{
    /**
     * @dataProvider paginationDataProvider
     *
     * @param int|null $limit
     * @param int|null $offset
     * @param int|null $expectedLimit
     * @param int|null $expectedOffset
     *
     * @return void
     */
    public function testToCriteriaShouldReturnCriteriaWithPagination(
        ?int $limit,
        ?int $offset,
        ?int $expectedLimit,
        ?int $expectedOffset
    ): void {
        // Arrange
        $filterTransfer = (new FilterTransfer())
            ->setLimit($limit)
            ->setOffset($offset);
        $propelFilterCriteria = new PropelFilterCriteria($filterTransfer);

        // Act
        $criteria = $propelFilterCriteria->toCriteria();

        // Assert
        $this->assertSame($expectedLimit, $criteria->getLimit());
        $this->assertSame($expectedOffset, $criteria->getOffset());
    }

    /**
     * @dataProvider sortingDataProvider
     * @dataProvider invalidSortingDataProvider
     *
     * @param string|null $orderByColumnName
     * @param string|null $orderDirection
     * @param string|null $expectedOrderByColumnName
     * @param string|null $expectedOrderDirection
     *
     * @return void
     */
    public function testToCriteriaShouldReturnCriteriaWithSorting(
        ?string $orderByColumnName,
        ?string $orderDirection,
        ?string $expectedOrderByColumnName,
        ?string $expectedOrderDirection
    ): void {
        // Arrange
        $filterTransfer = (new FilterTransfer())
            ->setOrderBy($orderByColumnName)
            ->setOrderDirection($orderDirection);
        $propelFilterCriteria = new PropelFilterCriteria($filterTransfer);
        $expectedOrderbyColumns = [];
        if ($expectedOrderByColumnName !== null && $expectedOrderDirection !== null) {
            $expectedOrderbyColumns = [sprintf('%s %s', $expectedOrderByColumnName, $expectedOrderDirection)];
        }

        // Act
        $criteria = $propelFilterCriteria->toCriteria();

        // Assert
        $this->assertSame($expectedOrderbyColumns, $criteria->getOrderByColumns());
    }

    /**
     * @return array<string, array<array-key, int|null>>
     */
    protected function paginationDataProvider(): array
    {
        return [
            'limit and offset are null' => [null, null, -1, 0],
            'limit and offset are set' => [5, 10, 5, 10],
            'limit is set, offset is null' => [10, null, 10, 0],
            'offset is set, limit is null' => [null, 20, -1, 20],
        ];
    }

    /**
     * @return array<string, array<array-key, string|null>>
     */
    protected function sortingDataProvider(): array
    {
        return [
            'empty column name and direction asc' => [null, Criteria::ASC, null, Criteria::ASC],
            'column name and empty direction' => ['created_at', null, 'created_at', null],
            'column name with underscore and direction asc' => ['created_at', Criteria::ASC, 'created_at', Criteria::ASC],
            'column name combined with table name and direction asc' => ['spy_product.created_at', Criteria::ASC, 'spy_product.created_at', Criteria::ASC],
            'column name and sort direction asc' => ['created_at', Criteria::ASC, 'created_at', Criteria::ASC],
            'column name and sort direction desc' => ['created_at', Criteria::DESC, 'created_at', Criteria::DESC],
        ];
    }

    /**
     * @return array<string, array<array-key, string|null>>
     */
    protected function invalidSortingDataProvider(): array
    {
        return [
            'invalid column name with SQL injection' => ['created_at; SELECT * FROM users;', Criteria::ASC, null, Criteria::ASC],
            'invalid column name with semicolon' => ['created_at;', Criteria::ASC, null, Criteria::ASC],
            'invalid column name with spaces' => ['created at', Criteria::ASC, null, Criteria::ASC],
            'invalid column name with special characters' => ['created_at#', Criteria::ASC, null, Criteria::ASC],
            'invalid column name with brackets' => ['my_column()', Criteria::ASC, null, Criteria::ASC],
            'invalid column name with quotes' => ["my_column'", Criteria::ASC, null, Criteria::ASC],
            'invalid column name with backticks' => ['`my_column`', Criteria::ASC, null, Criteria::ASC],
            'invalid column name with leading/trailing spaces' => [' column ', Criteria::ASC, null, Criteria::ASC],
            'invalid column name with non-word characters' => ['col_name$', Criteria::ASC, null, Criteria::ASC],
            'invalid column name with non-alphanumeric characters' => ['col:name', Criteria::ASC, null, Criteria::ASC],
            'invalid column name with non-ASCII characters' => ['col_名', Criteria::ASC, null, Criteria::ASC],
        ];
    }
}
