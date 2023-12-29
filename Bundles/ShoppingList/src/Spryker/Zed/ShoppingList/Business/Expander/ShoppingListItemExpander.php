<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemConditionsTransfer;
use Generated\Shared\Transfer\ShoppingListItemCriteriaTransfer;
use Spryker\Zed\ShoppingList\Business\Extractor\ShoppingListExtractorInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListItemExpander implements ShoppingListItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface
     */
    protected ShoppingListRepositoryInterface $shoppingListRepository;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\Extractor\ShoppingListExtractorInterface
     */
    protected ShoppingListExtractorInterface $shoppingListExtractor;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Business\Extractor\ShoppingListExtractorInterface $shoppingListExtractor
     */
    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListExtractorInterface $shoppingListExtractor
    ) {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->shoppingListExtractor = $shoppingListExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function expandShoppingListCollectionWithShoppingListItems(
        ShoppingListCollectionTransfer $shoppingListCollectionTransfer
    ): ShoppingListCollectionTransfer {
        $shoppingListIds = $this->shoppingListExtractor->extractShoppingListIdsFromShoppingListCollection($shoppingListCollectionTransfer);

        if (!$shoppingListIds) {
            return $shoppingListCollectionTransfer;
        }

        $shoppingListItemConditionsTransfer = (new ShoppingListItemConditionsTransfer())->setShoppingListIds($shoppingListIds);
        $shoppingListItemCollectionTransfer = $this->shoppingListRepository->getShoppingListItemCollection(
            (new ShoppingListItemCriteriaTransfer())->setShoppingListConditions($shoppingListItemConditionsTransfer),
        );
        $shoppingListItemTransfersGroupedByIdShoppingList = $this->getShoppingListItemsGroupedByIdShoppingList($shoppingListItemCollectionTransfer);

        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer */
        foreach ($shoppingListCollectionTransfer->getShoppingLists() as $shoppingListTransfer) {
            if (!isset($shoppingListItemTransfersGroupedByIdShoppingList[$shoppingListTransfer->getIdShoppingListOrFail()])) {
                continue;
            }

            $shoppingListTransfer->setItems(new ArrayObject($shoppingListItemTransfersGroupedByIdShoppingList[$shoppingListTransfer->getIdShoppingListOrFail()]));
        }

        return $shoppingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return array<int, list<\Generated\Shared\Transfer\ShoppingListItemTransfer>>
     */
    protected function getShoppingListItemsGroupedByIdShoppingList(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): array {
        $shoppingListItemTransfersGroupedByIdShoppingList = [];

        /** @var \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer */
        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemTransfersGroupedByIdShoppingList[$shoppingListItemTransfer->getFkShoppingListOrFail()][] = $shoppingListItemTransfer;
        }

        return $shoppingListItemTransfersGroupedByIdShoppingList;
    }
}
