<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySalesConnector\Business\CompanySalesConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterFieldTransfer;
use Spryker\Zed\CompanySalesConnector\Business\Expander\OrderSearchQueryExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanySalesConnector
 * @group Business
 * @group CompanySalesConnectorFacade
 * @group IsCompanyFilterApplicableTest
 * Add your own group annotations below this line
 */
class IsCompanyFilterApplicableTest extends Unit
{
    protected const UUID_SAMPLE = 'uuid-sample';
    protected const SEARCH_STRING = 'sample';

    /**
     * @var \SprykerTest\Zed\CompanySalesConnector\CompanySalesConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsCompanyFilterApplicableReturnsTrue(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY)
            ->setValue(static::UUID_SAMPLE);

        // Act
        $isApplicable = $this->tester->getFacade()->isCompanyFilterApplicable([
            $filterFieldTransfer,
        ]);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsCompanyFilterApplicableReturnsFalse(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType('fake')
            ->setValue(static::UUID_SAMPLE);

        // Act
        $isApplicable = $this->tester->getFacade()->isCompanyFilterApplicable([$filterFieldTransfer]);

        // Assert
        $this->assertFalse($isApplicable);
    }
}
