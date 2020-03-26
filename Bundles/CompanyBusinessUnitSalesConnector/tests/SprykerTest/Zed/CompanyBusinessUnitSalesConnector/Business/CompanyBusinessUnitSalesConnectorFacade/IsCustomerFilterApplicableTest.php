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
 * @group IsCustomerFilterApplicableTest
 * Add your own group annotations below this line
 */
class IsCustomerFilterApplicableTest extends Unit
{
    /**
     * @uses \Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY
     */
    protected const FILTER_FIELD_TYPE_COMPANY = 'company';

    protected const UUID_SAMPLE = 'uuid-sample';
    protected const SEARCH_STRING = 'sample';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsCustomerFilterApplicableReturnsTrueForBusinessUnitFilterCase(): void
    {
        // Arrange
        $allFilterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_ALL)
            ->setValue(static::SEARCH_STRING);
        $companyBusinessUnitFilterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT)
            ->setValue(static::UUID_SAMPLE);

        // Act
        $isApplicable = $this->tester->getFacade()->isCustomerFilterApplicable([
            $allFilterFieldTransfer,
            $companyBusinessUnitFilterFieldTransfer,
        ]);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsCustomerFilterApplicableReturnsTrueForCompanyFilterCase(): void
    {
        // Arrange
        $allFilterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_ALL)
            ->setValue(static::SEARCH_STRING);
        $companyFilterFieldTransfer = (new FilterFieldTransfer())
            ->setType(static::FILTER_FIELD_TYPE_COMPANY)
            ->setValue(static::UUID_SAMPLE);

        // Act
        $isApplicable = $this->tester->getFacade()->isCustomerFilterApplicable([
            $allFilterFieldTransfer,
            $companyFilterFieldTransfer,
        ]);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsCustomerFilterApplicableReturnsFalseForPresentCompanyFilterAndMissingSearchFilter(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(static::FILTER_FIELD_TYPE_COMPANY)
            ->setValue(static::UUID_SAMPLE);

        // Act
        $isApplicable = $this->tester->getFacade()->isCustomerFilterApplicable([
            $filterFieldTransfer,
        ]);

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsCustomerFilterApplicableReturnsFalseForPresentBusinessUnitFilterAndMissingSearchFilter(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT)
            ->setValue(static::UUID_SAMPLE);

        // Act
        $isApplicable = $this->tester->getFacade()->isCustomerFilterApplicable([
            $filterFieldTransfer,
        ]);

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsCustomerFilterApplicableReturnsFalseForPresentSearchFilter(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_ALL)
            ->setValue(static::SEARCH_STRING);

        // Act
        $isApplicable = $this->tester->getFacade()->isCustomerFilterApplicable([$filterFieldTransfer]);

        // Assert
        $this->assertFalse($isApplicable);
    }
}
