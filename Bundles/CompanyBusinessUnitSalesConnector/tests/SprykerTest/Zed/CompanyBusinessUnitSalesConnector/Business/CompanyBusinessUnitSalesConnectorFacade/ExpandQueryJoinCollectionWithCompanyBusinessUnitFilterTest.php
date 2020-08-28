<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Generated\Shared\Transfer\QueryJoinCollectionTransfer;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitSalesConnector
 * @group Business
 * @group CompanyBusinessUnitSalesConnectorFacade
 * @group ExpandQueryJoinCollectionWithCompanyBusinessUnitFilterTest
 * Add your own group annotations below this line
 */
class ExpandQueryJoinCollectionWithCompanyBusinessUnitFilterTest extends Unit
{
    /**
     * @uses \Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap::COL_COMPANY_BUSINESS_UNIT_UUID
     */
    protected const COLUMN_COMPANY_BUSINESS_UNIT_UUID = 'spy_sales_order.company_business_unit_uuid';

    /**
     * @uses \Propel\Runtime\ActiveQuery\Criteria::EQUAL
     */
    protected const COMPARISON_EQUAL = '=';

    protected const COMPANY_BUSINESS_UNIT_UUID = 'uuid-sample';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorBusinessTester
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
            ->setType(CompanyBusinessUnitSalesConnectorConfig::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT)
            ->setValue(static::COMPANY_BUSINESS_UNIT_UUID);

        // Act
        $queryJoinCollectionTransfer = $this->tester->getFacade()->expandQueryJoinCollectionWithCompanyBusinessUnitFilter(
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

        $this->assertSame(static::COMPANY_BUSINESS_UNIT_UUID, $queryWhereConditionTransfer->getValue());
        $this->assertSame(static::COLUMN_COMPANY_BUSINESS_UNIT_UUID, $queryWhereConditionTransfer->getColumn());
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
            ->setValue(static::COMPANY_BUSINESS_UNIT_UUID);

        // Act
        $queryJoinCollectionTransfer = $this->tester->getFacade()->expandQueryJoinCollectionWithCompanyBusinessUnitFilter(
            [$filterFieldTransfer],
            $queryJoinCollectionTransfer
        );

        // Assert
        $this->assertCount(0, $queryJoinCollectionTransfer->getQueryJoins());
    }
}
