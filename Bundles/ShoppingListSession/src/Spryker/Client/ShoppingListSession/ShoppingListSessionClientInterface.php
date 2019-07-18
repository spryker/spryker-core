<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListSession;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;

interface ShoppingListSessionClientInterface
{
    /**
     * Specification:
     *  - Gets Customer Shopping List Collection from Session or from Shopping List Client if data became outdated.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(): ShoppingListCollectionTransfer;
}
