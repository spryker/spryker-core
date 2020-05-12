<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface;

class ShoppingListProductOptionWriter implements ShoppingListProductOptionWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface
     */
    protected $shoppingListProductOptionEntityManager;

    /**
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface $shoppingListProductOptionEntityManager
     */
    public function __construct(
        ShoppingListProductOptionConnectorEntityManagerInterface $shoppingListProductOptionEntityManager
    ) {
        $this->shoppingListProductOptionEntityManager = $shoppingListProductOptionEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItemProductOptions(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemTransfer) {
            $this->executeSaveShoppingListItemProductOptionsTransaction($shoppingListItemTransfer);
        });

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function saveShoppingListItemProductOptionsInBulk(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemCollectionTransfer) {
            $this->executeSaveShoppingListItemProductOptionsTransactionInBulk($shoppingListItemCollectionTransfer);

            return $shoppingListItemCollectionTransfer;
        });
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return void
     */
    public function removeShoppingListItemProductOptions(int $idShoppingListItem): void
    {
        $this->shoppingListProductOptionEntityManager
            ->removeShoppingListItemProductOptions($idShoppingListItem);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    protected function executeSaveShoppingListItemProductOptionsTransaction(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $shoppingListItemTransfer->requireIdShoppingListItem();

        $this->removeShoppingListItemProductOptions($shoppingListItemTransfer->getIdShoppingListItem());

        foreach ($shoppingListItemTransfer->getProductOptions() as $productOptionTransfer) {
            $this->shoppingListProductOptionEntityManager
                ->saveShoppingListItemProductOption(
                    $shoppingListItemTransfer->getIdShoppingListItem(),
                    $productOptionTransfer->getIdProductOptionValue()
                );
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShoppingListItemTransfer[] $shoppingListItemTransfers
     *
     * @return void
     */
    protected function removeShoppingListItemProductOptionsInBulk(ArrayObject $shoppingListItemTransfers): void
    {
        $shoppingListItemIds = [];
        foreach ($shoppingListItemTransfers as $shoppingListItemTransfer) {
            $shoppingListItemIds[] = $shoppingListItemTransfer
                ->requireIdShoppingListItem()
                ->getIdShoppingListItem();
        }

        $this->shoppingListProductOptionEntityManager
            ->removeShoppingListItemProductOptionsByShoppingListItemIds($shoppingListItemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return void
     */
    protected function executeSaveShoppingListItemProductOptionsTransactionInBulk(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): void
    {
        $this->removeShoppingListItemProductOptionsInBulk($shoppingListItemCollectionTransfer->getItems());

        $this->shoppingListProductOptionEntityManager
            ->saveShoppingListItemProductOptionInBulk($shoppingListItemCollectionTransfer->getItems());
    }
}
