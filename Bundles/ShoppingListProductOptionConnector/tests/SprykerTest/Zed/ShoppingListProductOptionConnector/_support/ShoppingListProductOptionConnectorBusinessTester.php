<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingListProductOptionConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Orm\Zed\ShoppingListProductOptionConnector\Persistence\SpyShoppingListProductOptionQuery;

/**
 * Inherited Methods
 *
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
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOptionConnectorFacadeInterface getFacade()
 */
class ShoppingListProductOptionConnectorBusinessTester extends Actor
{
    use _generated\ShoppingListProductOptionConnectorBusinessTesterActions;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueTransfer
     */
    public function createProductOptionGroupValueTransfer(array $override): ProductOptionValueTransfer
    {
        $productOptionGroupTransfer = $this->haveProductOptionGroupWithValues(
            $this->getOverrideGroup($override),
            $this->getOverrideValues($override)
        );

        $productOptionValueTransfer = $productOptionGroupTransfer->getProductOptionValues()->offsetGet(0);

        return $productOptionValueTransfer;
    }

    /**
     * @param int $idShoppingListItem
     * @param int $idProductOptionValue
     *
     * @return void
     */
    public function assureShoppingListProductOptionConnector(int $idShoppingListItem, int $idProductOptionValue): void
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
    public function cleanUpShoppingListProductOptionConnectorByIdShoppingListItem(int $idShoppingListItem): void
    {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setIdShoppingListItem($idShoppingListItem);

        $this->getFacade()
            ->saveShoppingListItemProductOptions($shoppingListItemTransfer);
    }

    /**
     * @param array $override
     *
     * @return array
     */
    protected function getOverrideGroup(array $override): array
    {
        $isGroupActive = $override[ProductOptionGroupTransfer::ACTIVE] ?? true;

        return [
            ProductOptionGroupTransfer::ACTIVE => $isGroupActive,
        ];
    }

    /**
     * @param array $override
     *
     * @return array
     */
    protected function getOverrideValues(array $override): array
    {
        $sku = $override[ProductOptionValueTransfer::SKU];

        return [
            [
                [
                    ProductOptionValueTransfer::SKU => $sku,
                ],
                [
                    [],
                ],
            ],
        ];
    }
}
