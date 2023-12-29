<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Reader;

use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListConditionsTransfer;
use Generated\Shared\Transfer\ShoppingListCriteriaTransfer;
use Spryker\Zed\ShoppingList\Business\Expander\ShoppingListItemExpanderInterface;
use Spryker\Zed\ShoppingList\Business\Filter\ShoppingListFilterInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListCollectionReader implements ShoppingListCollectionReaderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingList\Business\Expander\ShoppingListItemExpanderInterface
     */
    protected ShoppingListItemExpanderInterface $shoppingListItemExpander;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface
     */
    protected ShoppingListRepositoryInterface $shoppingListRepository;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\Filter\ShoppingListFilterInterface
     */
    protected ShoppingListFilterInterface $shoppingListFilter;

    /**
     * @param \Spryker\Zed\ShoppingList\Business\Expander\ShoppingListItemExpanderInterface $shoppingListItemExpander
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Business\Filter\ShoppingListFilterInterface $shoppingListFilter
     */
    public function __construct(
        ShoppingListItemExpanderInterface $shoppingListItemExpander,
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListFilterInterface $shoppingListFilter
    ) {
        $this->shoppingListItemExpander = $shoppingListItemExpander;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->shoppingListFilter = $shoppingListFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCriteriaTransfer $shoppingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getShoppingListCollection(ShoppingListCriteriaTransfer $shoppingListCriteriaTransfer): ShoppingListCollectionTransfer
    {
        $customerShoppingListCriteriaTransfer = (new ShoppingListCriteriaTransfer())->fromArray($shoppingListCriteriaTransfer->toArray());
        if ($customerShoppingListCriteriaTransfer->getShoppingListConditions()) {
            $customerShoppingListCriteriaTransfer->getShoppingListConditionsOrFail()
                ->setBlacklistCompanyUserIds([])
                ->setCompanyUserIds([])
                ->setCompanyBusinessUnitIds([]);
        }

        $shoppingListCollectionTransfer = $this->shoppingListRepository->getShoppingListCollection($customerShoppingListCriteriaTransfer);
        $shoppingListConditionsTransfer = $shoppingListCriteriaTransfer->getShoppingListConditions();

        if (!$shoppingListConditionsTransfer) {
            return $shoppingListCollectionTransfer;
        }

        $shoppingListCollectionTransfer = $this->addSharedShoppingListsToShoppingListCollection(
            $shoppingListConditionsTransfer,
            $shoppingListCollectionTransfer,
        );

        if ($shoppingListConditionsTransfer->getWithExcludedBlacklistedShoppingLists() && $shoppingListConditionsTransfer->getBlacklistCompanyUserIds()) {
            $blacklistedShoppingListConditionsTransfer = (new ShoppingListConditionsTransfer())
                ->setBlacklistCompanyUserIds($shoppingListConditionsTransfer->getBlacklistCompanyUserIds());

            $shoppingListCollectionTransfer = $this->shoppingListFilter->filterBlacklistedShoppingListsFromShoppingListCollection(
                (new ShoppingListCriteriaTransfer())->setShoppingListConditions($blacklistedShoppingListConditionsTransfer),
                $shoppingListCollectionTransfer,
            );
        }

        if ($shoppingListConditionsTransfer->getWithShoppingListItems()) {
            $shoppingListCollectionTransfer = $this->shoppingListItemExpander->expandShoppingListCollectionWithShoppingListItems(
                $shoppingListCollectionTransfer,
            );
        }

        return $shoppingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListConditionsTransfer $shoppingListConditionsTransfer
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function addSharedShoppingListsToShoppingListCollection(
        ShoppingListConditionsTransfer $shoppingListConditionsTransfer,
        ShoppingListCollectionTransfer $shoppingListCollectionTransfer
    ): ShoppingListCollectionTransfer {
        $customerSharedShoppingListCollectionTransfer = new ShoppingListCollectionTransfer();
        $companyBusinessUnitSharedShoppingListCollectionTransfer = new ShoppingListCollectionTransfer();

        if ($shoppingListConditionsTransfer->getWithCustomerSharedShoppingLists() && $shoppingListConditionsTransfer->getCompanyUserIds()) {
            $customerSharedShoppingListConditionsTransfer = (new ShoppingListConditionsTransfer())
                ->setCompanyUserIds($shoppingListConditionsTransfer->getCompanyUserIds());

            $customerSharedShoppingListCollectionTransfer = $this->shoppingListRepository->getShoppingListCollection(
                (new ShoppingListCriteriaTransfer())->setShoppingListConditions($customerSharedShoppingListConditionsTransfer),
            );
        }

        if ($shoppingListConditionsTransfer->getWithBusinessUnitSharedShoppingLists() && $shoppingListConditionsTransfer->getCompanyBusinessUnitIds()) {
            $companyBusinessUnitSharedShoppingListConditionsTransfer = (new ShoppingListConditionsTransfer())
                ->setCompanyBusinessUnitIds($shoppingListConditionsTransfer->getCompanyBusinessUnitIds());

            $companyBusinessUnitSharedShoppingListCollectionTransfer = $this->shoppingListRepository->getShoppingListCollection(
                (new ShoppingListCriteriaTransfer())->setShoppingListConditions($companyBusinessUnitSharedShoppingListConditionsTransfer),
            );
        }

        return $this->mergeShoppingListCollections(
            $shoppingListCollectionTransfer,
            $customerSharedShoppingListCollectionTransfer,
            $companyBusinessUnitSharedShoppingListCollectionTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer ...$shoppingListTransferCollections
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function mergeShoppingListCollections(ShoppingListCollectionTransfer ...$shoppingListTransferCollections): ShoppingListCollectionTransfer
    {
        $mergedShoppingListCollection = new ShoppingListCollectionTransfer();
        $mergedShoppingListIds = [];

        foreach ($shoppingListTransferCollections as $shoppingListCollection) {
            /** @var \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer */
            foreach ($shoppingListCollection->getShoppingLists() as $shoppingListTransfer) {
                if (isset($mergedShoppingListIds[$shoppingListTransfer->getIdShoppingListOrFail()])) {
                    continue;
                }

                $mergedShoppingListCollection->addShoppingList($shoppingListTransfer);
                $mergedShoppingListIds[$shoppingListTransfer->getIdShoppingListOrFail()] = true;
            }
        }

        return $mergedShoppingListCollection;
    }
}
