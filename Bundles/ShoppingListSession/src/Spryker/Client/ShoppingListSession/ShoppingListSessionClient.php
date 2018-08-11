<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ShoppingListSession\ShoppingListSessionFactory getFactory()
 */
class ShoppingListSessionClient extends AbstractClient implements ShoppingListSessionClientInterface
{
    /**
     * Specification:
     *  - Gets Customer Shopping List Collection from Session or from Storage if data became outdated.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingLists(): ShoppingListCollectionTransfer
    {
        return $this->getFactory()->createShoppingListSessionReader()->getCustomerShoppingListCollection();
    }
}
