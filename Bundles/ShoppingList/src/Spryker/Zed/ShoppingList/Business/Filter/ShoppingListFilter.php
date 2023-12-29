<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Filter;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCriteriaTransfer;
use Spryker\Zed\ShoppingList\Business\Extractor\ShoppingListExtractorInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListFilter implements ShoppingListFilterInterface
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
     * @param \Generated\Shared\Transfer\ShoppingListCriteriaTransfer $shoppingListCriteriaTransfer
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function filterBlacklistedShoppingListsFromShoppingListCollection(
        ShoppingListCriteriaTransfer $shoppingListCriteriaTransfer,
        ShoppingListCollectionTransfer $shoppingListCollectionTransfer
    ): ShoppingListCollectionTransfer {
        $blacklistedShoppingListCollectionTransfer = $this->shoppingListRepository->getShoppingListCollection($shoppingListCriteriaTransfer);
        $blacklistedShoppingListIds = $this->shoppingListExtractor->extractShoppingListIdsFromShoppingListCollection($blacklistedShoppingListCollectionTransfer);
        $filteredShoppingListCollection = new ShoppingListCollectionTransfer();

        /** @var \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer */
        foreach ($shoppingListCollectionTransfer->getShoppingLists() as $shoppingListTransfer) {
            if (in_array($shoppingListTransfer->getIdShoppingListOrFail(), $blacklistedShoppingListIds, true)) {
                continue;
            }

            $filteredShoppingListCollection->addShoppingList($shoppingListTransfer);
        }

        return $filteredShoppingListCollection;
    }
}
