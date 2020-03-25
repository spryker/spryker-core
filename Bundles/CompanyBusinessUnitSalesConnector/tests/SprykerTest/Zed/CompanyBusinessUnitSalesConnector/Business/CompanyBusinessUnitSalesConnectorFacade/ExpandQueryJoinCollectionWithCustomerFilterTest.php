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
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpander;

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
    protected const SEARCH_STRING = 'sample';
    protected const COLUMN_FULL_NAME = 'full_name';
    protected const COLUMN_FULL_NAME_EXPRESSION = 'CONCAT(first_name,\' \', last_name)';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorTester
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
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_ALL)
            ->setValue('sample');

        // Act
        $queryJoinCollectionTransfer = $this->tester->getFacade()->expandQueryJoinCollectionWithCustomerFilter(
            [$filterFieldTransfer],
            $queryJoinCollectionTransfer
        );

        // Assert
        $this->assertCount(2, $queryJoinCollectionTransfer->getQueryJoins());

        $queryJoinIterator = $queryJoinCollectionTransfer->getQueryJoins()->getIterator();
        /**
         * @var \Generated\Shared\Transfer\QueryJoinTransfer $queryJoinTransfer
         */
        $queryJoinTransfer = $queryJoinIterator->current();

        $this->assertCount(1, $queryJoinTransfer->getQueryWhereConditions());

        /**
         * @var \Generated\Shared\Transfer\QueryWhereConditionTransfer $queryWhereConditionTransfer
         */
        $queryWhereConditionTransfer = $queryJoinTransfer->getQueryWhereConditions()->getIterator()->current();

        $this->assertCustomerEmailQueryWhereConditionTransfer($queryWhereConditionTransfer);

        $queryJoinIterator->next();
        $queryJoinTransfer = $queryJoinIterator->current();

        $this->assertCount(1, $queryJoinTransfer->getQueryWhereConditions());

        /**
         * @var \Generated\Shared\Transfer\QueryWhereConditionTransfer $queryWhereConditionTransfer
         */
        $queryWhereConditionTransfer = $queryJoinTransfer->getQueryWhereConditions()->getIterator()->current();

        $this->assertCustomerNameQueryWhereConditionTransfer($queryWhereConditionTransfer);
        $this->assertSame([static::COLUMN_FULL_NAME => static::COLUMN_FULL_NAME_EXPRESSION], $queryJoinTransfer->getWithColumns());
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
     *
     * @return void
     */
    protected function assertCustomerEmailQueryWhereConditionTransfer(QueryWhereConditionTransfer $queryWhereConditionTransfer): void
    {
        $this->assertSame(OrderSearchQueryExpander::CONDITION_GROUP_ALL, $queryWhereConditionTransfer->getMergeWithCondition());
        $this->assertSame('email', $queryWhereConditionTransfer->getColumn());
        $this->assertSame(static::SEARCH_STRING, $queryWhereConditionTransfer->getValue());
    }

    /**
     * @param \Generated\Shared\Transfer\QueryWhereConditionTransfer $queryWhereConditionTransfer
     *
     * @return void
     */
    protected function assertCustomerNameQueryWhereConditionTransfer(QueryWhereConditionTransfer $queryWhereConditionTransfer): void
    {
        $this->assertSame(OrderSearchQueryExpander::CONDITION_GROUP_ALL, $queryWhereConditionTransfer->getMergeWithCondition());
        $this->assertSame(static::COLUMN_FULL_NAME_EXPRESSION, $queryWhereConditionTransfer->getColumn());
        $this->assertSame(static::SEARCH_STRING, $queryWhereConditionTransfer->getValue());
    }
}
