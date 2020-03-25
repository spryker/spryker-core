<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterFieldCheckRequestTransfer;
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
 * @group IsCompanyRelatedFiltersSetTest
 * Add your own group annotations below this line
 */
class IsCompanyRelatedFiltersSetTest extends Unit
{
    /**
     * @uses \Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY
     */
    protected const FILTER_FIELD_TYPE_COMPANY = 'company';

    protected const UUID_SAMPLE = 'uuid-sample';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsCompanyRelatedFiltersSetReturnsSuccessfulResponseForBusinessUnitFilter(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT)
            ->setValue(static::UUID_SAMPLE);

        $filterFieldCheckRequestTransfer = (new FilterFieldCheckRequestTransfer())
            ->addFilterField($filterFieldTransfer);

        // Act
        $filterFieldCheckResponseTransfer = $this->tester->getFacade()->isCompanyRelatedFiltersSet($filterFieldCheckRequestTransfer);

        // Assert
        $this->assertTrue($filterFieldCheckResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testIsCompanyRelatedFiltersSetReturnsSuccessfulResponseForCompanyFilter(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(static::FILTER_FIELD_TYPE_COMPANY)
            ->setValue(static::UUID_SAMPLE);

        $filterFieldCheckRequestTransfer = (new FilterFieldCheckRequestTransfer())
            ->addFilterField($filterFieldTransfer);

        // Act
        $filterFieldCheckResponseTransfer = $this->tester->getFacade()->isCompanyRelatedFiltersSet($filterFieldCheckRequestTransfer);

        // Assert
        $this->assertTrue($filterFieldCheckResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testIsCompanyRelatedFiltersSetReturnsNotSuccessfulResponseForIrrelevantFilter(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType('another')
            ->setValue(static::UUID_SAMPLE);

        $filterFieldCheckRequestTransfer = (new FilterFieldCheckRequestTransfer())
            ->addFilterField($filterFieldTransfer);

        // Act
        $filterFieldCheckResponseTransfer = $this->tester->getFacade()->isCompanyRelatedFiltersSet($filterFieldCheckRequestTransfer);

        // Assert
        $this->assertFalse($filterFieldCheckResponseTransfer->getIsSuccessful());
    }
}
