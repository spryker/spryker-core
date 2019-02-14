<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Persistence;

interface ShoppingListProductOptionConnectorRepositoryInterface
{
    /**
     * @param int $idShoppingListItem
     *
     * @return int[]
     */
    public function getShoppingListItemProductOptionIdsByIdShoppingListItem(int $idShoppingListItem): array;

    /**
     * @param int $idShoppingListItem
     *
     * @return string
     */
    public function getShoppingListItemProductAbstractSkuByIdShoppingListItem(int $idShoppingListItem): string;
}
