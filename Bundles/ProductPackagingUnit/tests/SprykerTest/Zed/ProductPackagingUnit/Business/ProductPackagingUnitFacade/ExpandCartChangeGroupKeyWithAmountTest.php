<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use Codeception\Test\Unit;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group ExpandCartChangeGroupKeyWithAmountTest
 * Add your own group annotations below this line
 */
class ExpandCartChangeGroupKeyWithAmountTest extends Unit
{
    /**
     * @var string
     */
    protected const GROUP_KEY = 'GROUP_KEY_DUMMY';

    /**
     * @var string
     */
    protected const GROUP_KEY_FORMAT = '%s_amount_%s_sales_unit_id_%s';

    /**
     * @var int
     */
    protected const AMOUNT_VALUE = 5;

    /**
     * @var int
     */
    protected const SALES_UNIT_ID = 5;

    /**
     * @var int
     */
    protected const ITEM_QUANTITY = 2;

    /**
     * @var int
     */
    protected const PACKAGE_AMOUNT = 4;

    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandCartChangeGroupKeyWithAmountSalesUnitNoSalesUnitIsDefined(): void
    {
        // Arrange
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithoutAmountSalesUnitForGroupKeyGeneration(static::GROUP_KEY, static::PACKAGE_AMOUNT, static::ITEM_QUANTITY);

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->expandCartChangeGroupKeyWithAmount($cartChangeTransfer);
        $itemTransfer = $cartChangeTransfer->getItems()[0];

        // Assert
        $this->assertSame(static::GROUP_KEY, $itemTransfer->getGroupKey());
    }

    /**
     * @return void
     */
    public function testExpandCartChangeGroupKeyWithAmountSalesUnitIfSalesUnitIsDefined(): void
    {
        // Arrange
        $expectedGroupKey = sprintf(static::GROUP_KEY_FORMAT, static::GROUP_KEY, static::ITEM_QUANTITY, static::SALES_UNIT_ID);
        $cartChangeTransfer = $this->tester->createCartChangeTransferWithAmountSalesUnitForGroupKeyGeneration(static::GROUP_KEY, static::PACKAGE_AMOUNT, static::ITEM_QUANTITY, static::SALES_UNIT_ID);

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->expandCartChangeGroupKeyWithAmount($cartChangeTransfer);
        $itemTransfer = $cartChangeTransfer->getItems()[0];

        // Assert
        $this->assertSame($expectedGroupKey, $itemTransfer->getGroupKey());
    }
}
