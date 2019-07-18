<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListProductOptionConnector;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShoppingListProductOptionConnector\Mapper\QuoteItemToItemMapper;
use Spryker\Client\ShoppingListProductOptionConnector\Mapper\QuoteItemToItemMapperInterface;
use Spryker\Client\ShoppingListProductOptionConnector\Mapper\ShoppingListItemToItemMapper;
use Spryker\Client\ShoppingListProductOptionConnector\Mapper\ShoppingListItemToItemMapperInterface;

class ShoppingListProductOptionConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ShoppingListProductOptionConnector\Mapper\QuoteItemToItemMapperInterface
     */
    public function createQuoteItemToItemMapper(): QuoteItemToItemMapperInterface
    {
        return new QuoteItemToItemMapper();
    }

    /**
     * @return \Spryker\Client\ShoppingListProductOptionConnector\Mapper\ShoppingListItemToItemMapperInterface
     */
    public function createShoppingListItemToItemMapper(): ShoppingListItemToItemMapperInterface
    {
        return new ShoppingListItemToItemMapper();
    }
}
