<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySalesConnector\Business\CompanySalesConnectorFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterFieldTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanySalesConnector
 * @group Business
 * @group CompanySalesConnectorFacade
 * @group IsFilterFieldSetTest
 * Add your own group annotations below this line
 */
class IsFilterFieldSetTest extends Unit
{
    protected const SAMPLE_TYPE = 'sample';

    /**
     * @var \SprykerTest\Zed\CompanySalesConnector\CompanySalesConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsFilterFieldSetReturnsTrue(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(static::SAMPLE_TYPE);

        // Act
        $isApplicable = $this->tester->getFacade()->isFilterFieldSet([$filterFieldTransfer], static::SAMPLE_TYPE);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsFilterFieldSetReturnsFalse(): void
    {
        // Arrange
        $filterFieldTransfer = (new FilterFieldTransfer())
            ->setType(static::SAMPLE_TYPE);

        // Act
        $isApplicable = $this->tester->getFacade()->isFilterFieldSet([$filterFieldTransfer], 'fake');

        // Assert
        $this->assertFalse($isApplicable);
    }
}
