<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Calculation;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\Calculation\Business\CalculationFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CalculationBusinessTester extends Actor
{
    use _generated\CalculationBusinessTesterActions;

    /**
     * @param array $itemData
     * @param array $calculatedDiscountsData
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function createItemTransferWithCalculatedDiscounts(array $itemData = [], array $calculatedDiscountsData = []): ItemTransfer
    {
        $itemTransfer = (new ItemTransfer())->fromArray($itemData);

        $calculatedDiscounts = [];
        foreach ($calculatedDiscountsData as $calculatedDiscountData) {
            $calculatedDiscounts[] = (new CalculatedDiscountTransfer())->fromArray($calculatedDiscountData);
        }

        $itemTransfer->setCalculatedDiscounts(new ArrayObject($calculatedDiscounts));

        return $itemTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<\Generated\Shared\Transfer\DiscountTransfer> $cartRuleDiscountTransfers
     * @param array<\Generated\Shared\Transfer\DiscountTransfer> $voucherDiscountTransfers
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function createCalculableObjectTransferWithItemsAndDiscounts(
        array $itemTransfers,
        array $cartRuleDiscountTransfers,
        array $voucherDiscountTransfers
    ): CalculableObjectTransfer {
        return (new CalculableObjectTransfer())
            ->setItems(new ArrayObject($itemTransfers))
            ->setCartRuleDiscounts(new ArrayObject($cartRuleDiscountTransfers))
            ->setVoucherDiscounts(new ArrayObject($voucherDiscountTransfers));
    }
}
