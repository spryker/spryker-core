<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Generated\Shared\Transfer\QueryWhereConditionTransfer;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitSalesConnector
 * @group Business
 * @group CompanyBusinessUnitSalesConnectorFacade
 * @group ExpandQueryJoinCollectionWithCustomerFilterTest
 * Add your own group annotations below this line
 */
class ExpandQueryJoinCollectionWithCustomerFilterTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Sales\Persistence\Propel\QueryBuilder\OrderSearchFilterFieldQueryBuilder::CONDITION_GROUP_ALL
     * @var string
     */
    protected const CONDITION_GROUP_ALL = 'CONDITION_GROUP_ALL';

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpander::COLUMN_FULL_NAME
     * @var string
     */
    protected const COLUMN_FULL_NAME = 'full_name';

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpander::COLUMN_EMAIL
     * @var string
     */
    protected const COLUMN_EMAIL = 'spy_sales_order.email';

    /**
     * @see \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpander::getConcatenatedFullNameColumn()
     * @var string
     */
    protected const COLUMN_FULL_NAME_EXPRESSION = 'CONCAT(first_name,\' \', last_name)';

    /**
     * @var string
     */
    protected const SEARCH_STRING = 'sample';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandQueryJoinCollectionWithCustomerFilterExpandsCollection(): void
    {
        // Arrange
        $queryJoinCollectionTransfer = new QueryJoinCollectionTransfer();

        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(CompanyBusinessUnitSalesConnectorConfig::FILTER_FIELD_TYPE_ALL)
            ->setValue(static::SEARCH_STRING);

        // Act
        $queryJoinCollectionTransfer = $this->tester->getFacade()->expandQueryJoinCollectionWithCustomerFilter(
            [$filterFieldTransfer],
            $queryJoinCollectionTransfer
        );

        // Assert
        $this->assertCount(1, $queryJoinCollectionTransfer->getQueryJoins());

        /**
         * @var \Generated\Shared\Transfer\QueryJoinTransfer $queryJoinTransfer
         */
        $queryJoinTransfer = $queryJoinCollectionTransfer->getQueryJoins()->getIterator()->current();

        $this->assertCount(2, $queryJoinTransfer->getWhereConditions());
        $this->assertSame(
            [static::COLUMN_FULL_NAME => static::COLUMN_FULL_NAME_EXPRESSION],
            $queryJoinTransfer->getWithColumns()
        );

        $queryWhereConditionIterator = $queryJoinTransfer->getWhereConditions()->getIterator();

        /**
         * @var \Generated\Shared\Transfer\QueryWhereConditionTransfer $queryWhereConditionTransfer
         */
        $queryWhereConditionTransfer = $queryWhereConditionIterator->current();

        $this->assertQueryWhereConditionTransfer(
            $queryWhereConditionTransfer,
            static::COLUMN_FULL_NAME_EXPRESSION,
            static::SEARCH_STRING
        );

        $queryWhereConditionIterator->next();
        $queryWhereConditionTransfer = $queryWhereConditionIterator->current();

        $this->assertQueryWhereConditionTransfer(
            $queryWhereConditionTransfer,
            static::COLUMN_EMAIL,
            static::SEARCH_STRING
        );
    }

    /**
     * @return void
     */
    public function testExpandQueryJoinCollectionWithCustomerFilterIgnoresIrrelevantFilterFields(): void
    {
        // Arrange
        $queryJoinCollectionTransfer = new QueryJoinCollectionTransfer();

        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType('fake')
            ->setValue(static::SEARCH_STRING);

        // Act
        $queryJoinCollectionTransfer = $this->tester->getFacade()->expandQueryJoinCollectionWithCustomerFilter(
            [$filterFieldTransfer],
            $queryJoinCollectionTransfer
        );

        // Assert
        $this->assertCount(0, $queryJoinCollectionTransfer->getQueryJoins());
    }

    /**
     * @param \Generated\Shared\Transfer\QueryWhereConditionTransfer $queryWhereConditionTransfer
     * @param string $expectedColumn
     * @param string $expectedValue
     *
     * @return void
     */
    protected function assertQueryWhereConditionTransfer(
        QueryWhereConditionTransfer $queryWhereConditionTransfer,
        string $expectedColumn,
        string $expectedValue
    ): void {
        $this->assertSame(static::CONDITION_GROUP_ALL, $queryWhereConditionTransfer->getMergeWithCondition());
        $this->assertSame($expectedColumn, $queryWhereConditionTransfer->getColumn());
        $this->assertSame($expectedValue, $queryWhereConditionTransfer->getValue());
    }
}
