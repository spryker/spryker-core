<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOption;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShoppingListProductOption\Dependency\Client\ShoppingListProductOptionToCartClientInterface;
use Spryker\Client\ShoppingListProductOption\Mapper\ShoppingListItemToItemMapper;
use Spryker\Client\ShoppingListProductOption\Mapper\ShoppingListItemToItemMapperInterface;

class ShoppingListProductOptionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ShoppingListProductOption\Mapper\ShoppingListItemToItemMapperInterface
     */
    public function getShoppingListItemToItemMapper(): ShoppingListItemToItemMapperInterface
    {
        return new ShoppingListItemToItemMapper(
            $this->getCartClient()
        );
    }

    /**
     * @return \Spryker\Client\ShoppingListProductOption\Dependency\Client\ShoppingListProductOptionToCartClientInterface
     */
    public function getCartClient(): ShoppingListProductOptionToCartClientInterface
    {
        return $this->getProvidedDependency(ShoppingListProductOptionDependencyProvider::CLIENT_CART);
    }
}
