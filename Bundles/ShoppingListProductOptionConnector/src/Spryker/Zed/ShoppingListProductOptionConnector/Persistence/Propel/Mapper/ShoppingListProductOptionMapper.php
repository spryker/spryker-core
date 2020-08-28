<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListProductOptionTransfer;
use Orm\Zed\ShoppingListProductOptionConnector\Persistence\SpyShoppingListProductOption;
use Propel\Runtime\Collection\ObjectCollection;

class ShoppingListProductOptionMapper
{
    /**
     * @param \Orm\Zed\ShoppingListProductOptionConnector\Persistence\SpyShoppingListProductOption[]|\Propel\Runtime\Collection\ObjectCollection $shoppingListProductOptionEntityCollection
     * @param \Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer
     */
    public function mapShoppingListProductOptionEntityCollectionToShoppingListProductOptionCollectionTransfer(
        ObjectCollection $shoppingListProductOptionEntityCollection,
        ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer
    ): ShoppingListProductOptionCollectionTransfer {
        foreach ($shoppingListProductOptionEntityCollection as $shoppingListProductOptionEntity) {
            $shoppingListProductOptionTransfer = $this->mapShoppingListProductOptionEntityToShoppingListProductOptionTransfer(
                $shoppingListProductOptionEntity,
                new ShoppingListProductOptionTransfer()
            );

            $shoppingListProductOptionCollectionTransfer->addShoppingListProductOption($shoppingListProductOptionTransfer);
        }

        return $shoppingListProductOptionCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ShoppingListProductOptionConnector\Persistence\SpyShoppingListProductOption $shoppingListProductOptionEntity
     * @param \Generated\Shared\Transfer\ShoppingListProductOptionTransfer $shoppingListProductOptionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListProductOptionTransfer
     */
    public function mapShoppingListProductOptionEntityToShoppingListProductOptionTransfer(
        SpyShoppingListProductOption $shoppingListProductOptionEntity,
        ShoppingListProductOptionTransfer $shoppingListProductOptionTransfer
    ): ShoppingListProductOptionTransfer {
        return $shoppingListProductOptionTransfer
            ->setIdShoppingListProductOption($shoppingListProductOptionEntity->getIdShoppingListItemProductOption())
            ->setIdProductOptionValue($shoppingListProductOptionEntity->getFkProductOptionValue())
            ->setIdShoppingListItem($shoppingListProductOptionEntity->getFkShoppingListItem());
    }
}
