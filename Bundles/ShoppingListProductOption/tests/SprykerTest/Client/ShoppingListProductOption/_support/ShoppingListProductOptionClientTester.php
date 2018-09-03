<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ShoppingListProductOption;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOptionValueTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ShoppingListProductOptionClientTester extends Actor
{
    use _generated\ShoppingListProductOptionClientTesterActions;

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueTransfer|null
     */
    public function createProductOptionGroupValueTransfer(string $sku): ?ProductOptionValueTransfer
    {
        $productOptionGroupTransfer = $this->haveProductOptionGroupWithValues(
            [],
            [
                [
                    [ProductOptionValueTransfer::SKU => $sku],
                    [
                        [],
                    ],
                ],
            ]
        );

        $ProductOptionValueTransfer = $productOptionGroupTransfer->getProductOptionValues()->offsetGet(0);

        if (empty($ProductOptionValueTransfer)) {
            return null;
        }

        return $ProductOptionValueTransfer;
    }
}
