<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySalesConnector\Business\CompanySalesConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Spryker\Zed\CompanySalesConnector\CompanySalesConnectorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanySalesConnector
 * @group Business
 * @group CompanySalesConnectorFacade
 * @group ExpandQueryJoinCollectionWithCompanyFilterTest
 * Add your own group annotations below this line
 */
class ExpandQueryJoinCollectionWithCompanyFilterTest extends Unit
{
    /**
     * @see \Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap::COL_COMPANY_UUID
     */
    protected const COLUMN_COMPANY_UUID = 'spy_sales_order.company_uuid';

    /**
     * @see \Propel\Runtime\ActiveQuery\Criteria::EQUAL
     */
    protected const COMPARISON_EQUAL = '=';

    protected const UUID_SAMPLE = 'uuid-sample';

    /**
     * @var \SprykerTest\Zed\CompanySalesConnector\CompanySalesConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandQueryJoinCollectionWithCompanyBusinessUnitFilterExpandsCollection(): void
    {
        // Arrange
        $queryJoinCollectionTransfer = new QueryJoinCollectionTransfer();

        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(CompanySalesConnectorConfig::FILTER_FIELD_TYPE_COMPANY)
            ->setValue(static::UUID_SAMPLE);

        // Act
        $queryJoinCollectionTransfer = $this->tester->getFacade()->expandQueryJoinCollectionWithCompanyFilter(
            [$filterFieldTransfer],
            $queryJoinCollectionTransfer
        );

        // Assert
        $this->assertCount(1, $queryJoinCollectionTransfer->getQueryJoins());

        /**
         * @var \Generated\Shared\Transfer\QueryJoinTransfer $queryJoinTransfer
         */
        $queryJoinTransfer = $queryJoinCollectionTransfer->getQueryJoins()->getIterator()->current();

        $this->assertCount(1, $queryJoinTransfer->getWhereConditions());

        /**
         * @var \Generated\Shared\Transfer\QueryWhereConditionTransfer $queryWhereConditionTransfer
         */
        $queryWhereConditionTransfer = $queryJoinTransfer->getWhereConditions()->getIterator()->current();

        $this->assertSame(static::UUID_SAMPLE, $queryWhereConditionTransfer->getValue());
        $this->assertSame(static::COLUMN_COMPANY_UUID, $queryWhereConditionTransfer->getColumn());
        $this->assertSame(static::COMPARISON_EQUAL, $queryWhereConditionTransfer->getComparison());
    }

    /**
     * @return void
     */
    public function testExpandQueryJoinCollectionWithCompanyBusinessUnitFilterIgnoresIrrelevantFilterFields(): void
    {
        // Arrange
        $queryJoinCollectionTransfer = new QueryJoinCollectionTransfer();

        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType('fake')
            ->setValue(static::UUID_SAMPLE);

        // Act
        $queryJoinCollectionTransfer = $this->tester->getFacade()->expandQueryJoinCollectionWithCompanyFilter(
            [$filterFieldTransfer],
            $queryJoinCollectionTransfer
        );
        // Assert
        $this->assertCount(0, $queryJoinCollectionTransfer->getQueryJoins());
    }
}
