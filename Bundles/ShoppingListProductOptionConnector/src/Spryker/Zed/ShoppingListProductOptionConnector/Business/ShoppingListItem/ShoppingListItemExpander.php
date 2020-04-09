<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListItem;

use ArrayObject;
use Generated\Shared\Transfer\ProductOptionCollectionTransfer;
use Generated\Shared\Transfer\ProductOptionCriteriaTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionReaderInterface;
use Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface;

class ShoppingListItemExpander implements ShoppingListItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionReaderInterface
     */
    protected $shoppingListProductOptionReader;

    /**
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionReaderInterface $shoppingListProductOptionReader
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface $productOptionFacade
     */
    public function __construct(
        ShoppingListProductOptionReaderInterface $shoppingListProductOptionReader,
        ShoppingListProductOptionConnectorToProductOptionFacadeInterface $productOptionFacade
    ) {
        $this->shoppingListProductOptionReader = $shoppingListProductOptionReader;
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListItem\ShoppingListItemExpander::expandShoppingListItemCollectionWithProductOptions()} instead.
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function expandShoppingListItemWithProductOptions(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $productOptionCollectionTransfer = $this->shoppingListProductOptionReader
            ->getShoppingListItemProductOptionsByIdShoppingListItem($shoppingListItemTransfer);

        $shoppingListItemTransfer->setProductOptions($productOptionCollectionTransfer->getProductOptions());

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandShoppingListItemCollectionWithProductOptions(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        $shoppingListProductOptionCollectionTransfer = $this->shoppingListProductOptionReader
            ->getShoppingListProductOptionCollectionByShoppingListItemCollection($shoppingListItemCollectionTransfer);

        $uniqueProductOptionIds = $this
            ->getUniqueProductOptionIdsFromShoppingListProductOptionCollectionTransfer($shoppingListProductOptionCollectionTransfer);

        $indexedByIdsUniqueProductOptionTransfers = $this->indexUniqueProductOptionTransfersByIds(
            $uniqueProductOptionIds,
            $shoppingListItemCollectionTransfer
        );

        $groupedProductOptionIdsByShoppingListItemIds = $this
            ->groupProductOptionIdsByShoppingListItemIds($shoppingListProductOptionCollectionTransfer);

        $expandedShoppingListItemTransfers = new ArrayObject();
        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $expandedShoppingListItemTransfers->append(
                $this->mapShoppingListItemTransferWithProductOptions(
                    $shoppingListItemTransfer,
                    $groupedProductOptionIdsByShoppingListItemIds,
                    $indexedByIdsUniqueProductOptionTransfers
                )
            );
        }

        $shoppingListItemCollectionTransfer->setItems($expandedShoppingListItemTransfers);

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param int[][] $groupedProductOptionIdsByShoppingListItemIds
     * @param \Generated\Shared\Transfer\ProductOptionTransfer[] $indexedByIdsUniqueProductOptionTransfers
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function mapShoppingListItemTransferWithProductOptions(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        array $groupedProductOptionIdsByShoppingListItemIds,
        array $indexedByIdsUniqueProductOptionTransfers
    ): ShoppingListItemTransfer {
        $shoppingListItemTransfer->setProductOptions(new ArrayObject());
        if (!isset($groupedProductOptionIdsByShoppingListItemIds[$shoppingListItemTransfer->getIdShoppingListItem()])) {
            return $shoppingListItemTransfer;
        }

        foreach ($groupedProductOptionIdsByShoppingListItemIds[$shoppingListItemTransfer->getIdShoppingListItem()] as $productOptionId) {
            if (!isset($indexedByIdsUniqueProductOptionTransfers[$productOptionId])) {
                continue;
            }

            $shoppingListItemTransfer->getProductOptions()->append($indexedByIdsUniqueProductOptionTransfers[$productOptionId]);
        }

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer
     *
     * @return int[][]
     */
    protected function groupProductOptionIdsByShoppingListItemIds(
        ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer
    ): array {
        $groupedProductOptionIdsByShoppingListItemIds = [];
        foreach ($shoppingListProductOptionCollectionTransfer->getShoppingListProductOptions() as $shoppingListProductOptionTransfer) {
            if (!isset($groupedProductOptionIdsByShoppingListItemIds[$shoppingListProductOptionTransfer->getIdShoppingListItem()])) {
                $groupedProductOptionIdsByShoppingListItemIds[$shoppingListProductOptionTransfer->getIdShoppingListItem()] = [];
            }

            $groupedProductOptionIdsByShoppingListItemIds[$shoppingListProductOptionTransfer->getIdShoppingListItem()][]
                = $shoppingListProductOptionTransfer->getIdProductOptionValue();
        }

        return $groupedProductOptionIdsByShoppingListItemIds;
    }

    /**
     * @param int[] $uniqueProductOptionIds
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function indexUniqueProductOptionTransfersByIds(
        array $uniqueProductOptionIds,
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): array {
        $productOptionCriteriaTransfer = $this->expandProductOptionCriteriaTransferWithCurrencyParameters(
            new ProductOptionCriteriaTransfer(),
            $shoppingListItemCollectionTransfer
        );

        $productOptionCriteriaTransfer
            ->setProductOptionGroupIsActive(true)
            ->setProductOptionIds($uniqueProductOptionIds);

        $productOptionCollection = $this->productOptionFacade
            ->getProductOptionCollectionByProductOptionCriteria($productOptionCriteriaTransfer);

        return $this->indexProductOptionTransfersByIds($productOptionCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionCriteriaTransfer
     */
    protected function expandProductOptionCriteriaTransferWithCurrencyParameters(
        ProductOptionCriteriaTransfer $productOptionCriteriaTransfer,
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ProductOptionCriteriaTransfer {
        $shoppingListItemCollectionTransfer->requireItems();
        /** @var \Generated\Shared\Transfer\ShoppingListItemTransfer $firstShoppingListItemTransfer */
        $firstShoppingListItemTransfer = $shoppingListItemCollectionTransfer->getItems()->getIterator()->current();

        $productOptionCriteriaTransfer
            ->setCurrencyIsoCode($firstShoppingListItemTransfer->getCurrencyIsoCode())
            ->setPriceMode($firstShoppingListItemTransfer->getPriceMode());

        return $productOptionCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionCollectionTransfer $productOptionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function indexProductOptionTransfersByIds(ProductOptionCollectionTransfer $productOptionCollectionTransfer): array
    {
        $indexedByIdsProductOptionTransfers = [];
        foreach ($productOptionCollectionTransfer->getProductOptions() as $productOptionTransfer) {
            $indexedByIdsProductOptionTransfers[$productOptionTransfer->getIdProductOptionValue()] = $productOptionTransfer;
        }

        return $indexedByIdsProductOptionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer
     *
     * @return int[]
     */
    protected function getUniqueProductOptionIdsFromShoppingListProductOptionCollectionTransfer(
        ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer
    ): array {
        $productOptionIds = [];
        foreach ($shoppingListProductOptionCollectionTransfer->getShoppingListProductOptions() as $shoppingListProductOptionTransfer) {
            $productOptionIds[] = $shoppingListProductOptionTransfer->getIdProductOptionValue();
        }

        return array_unique($productOptionIds);
    }
}
