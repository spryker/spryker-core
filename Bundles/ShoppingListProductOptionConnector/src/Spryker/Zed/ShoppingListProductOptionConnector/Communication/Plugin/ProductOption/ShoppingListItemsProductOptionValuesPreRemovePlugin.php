<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Communication\Plugin\ProductOption;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionValuesPreRemovePluginInterface;

/**
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOptionConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorConfig getConfig()
 */
class ShoppingListItemsProductOptionValuesPreRemovePlugin extends AbstractPlugin implements ProductOptionValuesPreRemovePluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes deleted or deactivated product option values by ids from shopping list items.
     * - Product option values ids are taken from ProductOptionGroupTransfer::productOptionValuesToBeRemoved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function preRemove(ProductOptionGroupTransfer $productOptionGroupTransfer): void
    {
        $this->getFacade()
            ->deleteShoppingListItemProductOptionsByRemovedProductOptionValues($productOptionGroupTransfer);
    }
}
