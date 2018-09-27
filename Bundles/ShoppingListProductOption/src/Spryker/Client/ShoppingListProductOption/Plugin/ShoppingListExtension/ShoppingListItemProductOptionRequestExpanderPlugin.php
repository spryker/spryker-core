<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOption\Plugin\ShoppingListExtension;

use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemExpanderPluginInterface;

class ShoppingListItemProductOptionRequestExpanderPlugin implements ShoppingListItemExpanderPluginInterface
{
    protected const REQUEST_PARAM_PRODUCT_OPTION = 'product-option';

    /**
     * {@inheritdoc}
     * - Expands ShoppingListItemTransfer with product option IDs.
     * - Expects an array of product option IDs in `product-option` key of "params".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expand(ShoppingListItemTransfer $shoppingListItemTransfer, array $params): ShoppingListItemTransfer
    {
        foreach ($this->getIdProductOptions($params) as $idProductOption) {
            $shoppingListItemTransfer->addProductOption(
                (new ProductOptionTransfer())->setIdProductOptionValue($idProductOption)
            );
        }

        return $shoppingListItemTransfer;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function getIdProductOptions(array $params): array
    {
        if (isset($params[static::REQUEST_PARAM_PRODUCT_OPTION]) && is_array($params[static::REQUEST_PARAM_PRODUCT_OPTION])) {
            return array_filter($params[static::REQUEST_PARAM_PRODUCT_OPTION]);
        }

        return [];
    }
}
