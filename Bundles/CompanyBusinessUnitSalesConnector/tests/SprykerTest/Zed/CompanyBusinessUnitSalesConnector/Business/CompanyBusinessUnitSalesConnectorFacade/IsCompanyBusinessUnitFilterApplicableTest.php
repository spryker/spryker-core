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
 * @group IsCompanyBusinessUnitFilterApplicableTest
 * Add your own group annotations below this line
 */
class IsCompanyBusinessUnitFilterApplicableTest extends Unit
{
    protected const COMPANY_BUSINESS_UNIT_UUID = 'uuid-sample';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsCompanyBusinessUnitFilterApplicableReturnsTrue(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT)
            ->setValue(static::COMPANY_BUSINESS_UNIT_UUID);

        // Act
        $isApplicable = $this->tester->getFacade()->isCompanyBusinessUnitFilterApplicable([$filterFieldTransfer]);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsCompanyBusinessUnitFilterApplicableReturnsFalse(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType('fake')
            ->setValue(static::COMPANY_BUSINESS_UNIT_UUID);

        // Act
        $isApplicable = $this->tester->getFacade()->isCompanyBusinessUnitFilterApplicable([$filterFieldTransfer]);

        // Assert
        $this->assertFalse($isApplicable);
    }
}
