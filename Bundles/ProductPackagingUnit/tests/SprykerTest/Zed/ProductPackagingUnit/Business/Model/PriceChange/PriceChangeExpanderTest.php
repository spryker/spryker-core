<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business\Model\PriceChange;

use Codeception\Test\Unit;
use Spryker\Zed\ProductPackagingUnit\Business\Model\PriceChange\PriceChangeExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group Model
 * @group PriceChange
 * @group PriceChangeExpanderTest
 * Add your own group annotations below this line
 */
class PriceChangeExpanderTest extends Unit
{
    protected const EXPECTED_UNIT_NET_PRICE = 16;

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCustomAmountPriceIsCorrect(): void
    {
        $priceChangeExpander = new PriceChangeExpander();
        $cartChangeTransfer = $priceChangeExpander->setCustomAmountPrice($this->tester->getCartChangeTransfer());

        foreach ($cartChangeTransfer->getItems() as $item) {
            $this->assertEquals(static::EXPECTED_UNIT_NET_PRICE, $item->getUnitNetPrice());
        }
    }
}
