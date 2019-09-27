<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOptionConnector\ShoppingList;

use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ShoppingListExtension\Dependency\Plugin\ShoppingListItemMapperPluginInterface;

class ShoppingListItemProductOptionRequestMapperPlugin extends AbstractPlugin implements ShoppingListItemMapperPluginInterface
{
    protected const REQUEST_PARAM_PRODUCT_OPTION = 'product-option';

    /**
     * {@inheritDoc}
     * - Maps ShoppingListItemTransfer with product option IDs.
     * - Expects an array of product option IDs in "product-option" key of "params".
     *
     * @api
     *
     * @param array $params
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function map(array $params, ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        foreach ($this->getProductOptionIds($params) as $idProductOption) {
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
    protected function getProductOptionIds(array $params): array
    {
        if (isset($params[static::REQUEST_PARAM_PRODUCT_OPTION]) && is_array($params[static::REQUEST_PARAM_PRODUCT_OPTION])) {
            return array_filter($params[static::REQUEST_PARAM_PRODUCT_OPTION]);
        }

        return [];
    }
}
