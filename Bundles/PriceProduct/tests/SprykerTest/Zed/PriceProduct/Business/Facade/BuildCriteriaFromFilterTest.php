<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProduct\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PriceProductFilterBuilder;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\PriceProduct\PriceProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProduct
 * @group Business
 * @group Facade
 * @group BuildCriteriaFromFilterTest
 * Add your own group annotations below this line
 */
class BuildCriteriaFromFilterTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var \SprykerTest\Zed\PriceProduct\PriceProductBusinessTester
     */
    protected PriceProductBusinessTester $tester;

    /**
     * @return void
     */
    public function testBuildCriteriaFromFilter(): void
    {
        // Arrange
        /** @var \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer */
        $priceProductFilterTransfer = (new PriceProductFilterBuilder([
            'quantity' => rand(1, 100),
        ]))->build();
        $priceProductFilterTransfer->setStoreName($this->tester->haveStore([StoreTransfer::NAME => static::DEFAULT_STORE])->getName());

        // Act
        $priceProductCriteriaTransfer = $this->tester->getFacade()
            ->buildCriteriaFromFilter($priceProductFilterTransfer);

        // Assert
        $this->assertSame($priceProductFilterTransfer->getQuantity(), $priceProductCriteriaTransfer->getQuantity());
    }
}
