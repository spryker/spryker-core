<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade;

use Generated\Shared\Transfer\ShoppingListItemTransfer;

interface ShoppingListProductOptionConnectorToShoppingListFacadeInterface
{
    /**
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function findShoppingListItemById(int $idShoppingListItem): ShoppingListItemTransfer;
}
