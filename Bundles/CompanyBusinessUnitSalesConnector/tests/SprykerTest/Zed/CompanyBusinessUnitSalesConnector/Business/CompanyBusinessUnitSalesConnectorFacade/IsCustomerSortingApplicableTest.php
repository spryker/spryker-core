<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitSalesConnector
 * @group Business
 * @group CompanyBusinessUnitSalesConnectorFacade
 * @group IsCustomerSortingApplicableTest
 * Add your own group annotations below this line
 */
class IsCustomerSortingApplicableTest extends Unit
{
    /**
     * @uses \Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY
     */
    protected const FILTER_FIELD_TYPE_COMPANY = 'company';

    protected const UUID_SAMPLE = 'uuid-sample';
    protected const ORDER_BY_STRING = 'field::direction';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsCustomerSortingApplicableReturnsTrueForBusinessUnitFilterCase(): void
    {
        // Arrange
        $orderByFilterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_ORDER_BY)
            ->setValue(static::ORDER_BY_STRING);
        $companyBusinessUnitFilterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT)
            ->setValue(static::UUID_SAMPLE);

        // Act
        $isApplicable = $this->tester->getFacade()->isCustomerSortingApplicable([
            $orderByFilterFieldTransfer,
            $companyBusinessUnitFilterFieldTransfer,
        ]);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testisCustomerSortingApplicableReturnsTrueForCompanyFilterCase(): void
    {
        // Arrange
        $orderByFilterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_ORDER_BY)
            ->setValue(static::ORDER_BY_STRING);
        $companyFilterFieldTransfer = (new FilterFieldTransfer())
            ->setType(static::FILTER_FIELD_TYPE_COMPANY)
            ->setValue(static::UUID_SAMPLE);

        // Act
        $isApplicable = $this->tester->getFacade()->isCustomerSortingApplicable([
            $orderByFilterFieldTransfer,
            $companyFilterFieldTransfer,
        ]);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testisCustomerSortingApplicableReturnsFalseForPresentCompanyFilterAndMissingOrderByFilter(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(static::FILTER_FIELD_TYPE_COMPANY)
            ->setValue(static::UUID_SAMPLE);

        // Act
        $isApplicable = $this->tester->getFacade()->isCustomerSortingApplicable([
            $filterFieldTransfer,
        ]);

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testisCustomerSortingApplicableReturnsFalseForPresentBusinessUnitFilterAndMissingOrderByFilter(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT)
            ->setValue(static::UUID_SAMPLE);

        // Act
        $isApplicable = $this->tester->getFacade()->isCustomerSortingApplicable([
            $filterFieldTransfer,
        ]);

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testisCustomerSortingApplicableReturnsFalseForPresentOrderByFilter(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_ORDER_BY)
            ->setValue(static::ORDER_BY_STRING);

        // Act
        $isApplicable = $this->tester->getFacade()->isCustomerSortingApplicable([$filterFieldTransfer]);

        // Assert
        $this->assertFalse($isApplicable);
    }
}
