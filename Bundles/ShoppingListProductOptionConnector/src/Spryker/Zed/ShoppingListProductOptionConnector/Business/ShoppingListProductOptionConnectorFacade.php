<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOptionConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface getEntityManager()
 */
class ShoppingListProductOptionConnectorFacade extends AbstractFacade implements ShoppingListProductOptionConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItemProductOptions(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getFactory()
            ->createShoppingListProductOptionWriter()
            ->saveShoppingListItemProductOptions($shoppingListItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function removeShoppingListItemProductOptions(int $idShoppingListItem): void
    {
        $this->getFactory()
            ->createShoppingListProductOptionWriter()
            ->removeShoppingListItemProductOptions($idShoppingListItem);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expandShoppingListItemWithProductOptions(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getFactory()
            ->createShoppingListItemExpander()
            ->expandShoppingListItemWithProductOptions($shoppingListItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function mapCartItemProductOptionsToShoppingListItemProductOptions(ItemTransfer $itemTransfer, ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->getFactory()
            ->createCartItemToShoppingListItemMapper()
            ->map($itemTransfer, $shoppingListItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function deleteShoppingListItemProductOptionsByRemovedProductOptionValues(ProductOptionGroupTransfer $productOptionGroupTransfer): void
    {
        $this->getFactory()
            ->createProductOptionValuesRemover()
            ->deleteShoppingListItemProductOptionsByRemovedProductOptionValues($productOptionGroupTransfer);
    }
}
