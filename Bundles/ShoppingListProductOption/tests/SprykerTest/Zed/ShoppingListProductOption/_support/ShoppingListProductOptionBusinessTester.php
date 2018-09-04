<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingListProductOption;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Orm\Zed\ShoppingListProductOption\Persistence\SpyShoppingListProductOptionQuery;

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
 *
 * @method \Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOptionFacade getFacade()
 */
class ShoppingListProductOptionBusinessTester extends Actor
{
    use _generated\ShoppingListProductOptionBusinessTesterActions;

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

    /**
     * @param int $idShoppingListItem
     * @param int $idProductOptionValue
     *
     * @return void
     */
    public function assureShoppingListProductOption(int $idShoppingListItem, int $idProductOptionValue): void
    {
        (new SpyShoppingListProductOptionQuery())
            ->filterByFkShoppingListItem($idShoppingListItem)
            ->filterByFkProductOptionValue($idProductOptionValue)
            ->findOneOrCreate()
            ->save();
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function cleanUpShoppingListProductOptionByIdShoppingListItem(int $idShoppingListItem): void
    {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($idShoppingListItem);

        $this->getFacade()
            ->saveShoppingListItemProductOption($shoppingListItemTransfer);
    }
}
