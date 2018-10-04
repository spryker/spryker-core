<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOption;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShoppingListProductOption\Mapper\QuoteItemToItemMapper;
use Spryker\Client\ShoppingListProductOption\Mapper\QuoteItemToItemMapperInterface;
use Spryker\Client\ShoppingListProductOption\Mapper\ShoppingListItemToItemMapper;
use Spryker\Client\ShoppingListProductOption\Mapper\ShoppingListItemToItemMapperInterface;

class ShoppingListProductOptionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ShoppingListProductOption\Mapper\QuoteItemToItemMapperInterface
     */
    public function createQuoteItemToItemMapper(): QuoteItemToItemMapperInterface
    {
        return new QuoteItemToItemMapper();
    }

    /**
     * @return \Spryker\Client\ShoppingListProductOption\Mapper\ShoppingListItemToItemMapperInterface
     */
    public function createShoppingListItemToItemMapper(): ShoppingListItemToItemMapperInterface
    {
        return new ShoppingListItemToItemMapper();
    }
}
