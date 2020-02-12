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
    public function expandShoppingListItemCollectionWithProductOptions(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        $shoppingListProductOptionCollectionTransfer = $this->shoppingListProductOptionReader
            ->getShoppingListProductOptionCollectionByShoppingListItemCollection($shoppingListItemCollectionTransfer);

        $keyedUniqueProductOptionTransfers = $this
            ->getKeyedUniqueProductOptionTransfers(
                $shoppingListProductOptionCollectionTransfer,
                $shoppingListItemCollectionTransfer->getCurrencyIsoCode(),
                $shoppingListItemCollectionTransfer->getPriceMode()
            );

        $shoppingListItemIdsToProductOptionIdsMap = $this->getShoppingListItemIdsToProductOptionIdsMap($shoppingListProductOptionCollectionTransfer);

        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $productOptions = new ArrayObject();
            if (!isset($shoppingListItemIdsToProductOptionIdsMap[$shoppingListItemTransfer->getIdShoppingListItem()])) {
                continue;
            }

            foreach ($shoppingListItemIdsToProductOptionIdsMap[$shoppingListItemTransfer->getIdShoppingListItem()] as $productOptionId) {
                if (!isset($keyedUniqueProductOptionTransfers[$productOptionId])) {
                    continue;
                }

                $productOptions->append($keyedUniqueProductOptionTransfers[$productOptionId]);
            }

            $shoppingListItemTransfer->setProductOptions($productOptions);
        }

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer
     *
     * @return int[][]
     */
    protected function getShoppingListItemIdsToProductOptionIdsMap(ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer): array
    {
        $shoppingListItemIdsToProductOptionIdsMap = [];
        foreach ($shoppingListProductOptionCollectionTransfer->getShoppingListProductOptions() as $shoppingListProductOptionTransfer) {
            if (!isset($shoppingListItemIdsToProductOptionIdsMap[$shoppingListProductOptionTransfer->getIdShoppingListItem()])) {
                $shoppingListItemIdsToProductOptionIdsMap[$shoppingListProductOptionTransfer->getIdShoppingListItem()] = [];
            }

            $shoppingListItemIdsToProductOptionIdsMap[$shoppingListProductOptionTransfer->getIdShoppingListItem()][]
                = $shoppingListProductOptionTransfer->getIdProductOptionValue();
        }

        return $shoppingListItemIdsToProductOptionIdsMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer
     * @param string|null $currencyIsoCode
     * @param string|null $priceMode
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function getKeyedUniqueProductOptionTransfers(
        ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer,
        ?string $currencyIsoCode,
        ?string $priceMode
    ): array {
        $uniqueProductOptionIds = $this
            ->getUniqueProductOptionIdsFromShoppingListProductOptionCollection($shoppingListProductOptionCollectionTransfer);

        $productOptionCriteria = (new ProductOptionCriteriaTransfer())
            ->setCurrencyIsoCode($currencyIsoCode)
            ->setPriceMode($priceMode)
            ->setProductOptionGroupIsActive(true)
            ->setProductOptionIds($uniqueProductOptionIds);

        $productOptionCollection = $this->productOptionFacade
            ->getProductOptionCollectionByProductOptionCriteria($productOptionCriteria);

        return $this->getKeyedProductOptionTransfers($productOptionCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionCollectionTransfer $productOptionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function getKeyedProductOptionTransfers(ProductOptionCollectionTransfer $productOptionCollectionTransfer): array
    {
        $keyedProductOptionTransfers = [];
        foreach ($productOptionCollectionTransfer->getProductOptions() as $productOptionTransfer) {
            $keyedProductOptionTransfers[$productOptionTransfer->getIdProductOptionValue()] = $productOptionTransfer;
        }

        return $keyedProductOptionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer
     *
     * @return int[]
     */
    protected function getUniqueProductOptionIdsFromShoppingListProductOptionCollection(ShoppingListProductOptionCollectionTransfer $shoppingListProductOptionCollectionTransfer): array
    {
        $productOptionIds = [];
        foreach ($shoppingListProductOptionCollectionTransfer->getShoppingListProductOptions() as $shoppingListProductOptionTransfer) {
            $productOptionIds[] = $shoppingListProductOptionTransfer->getIdProductOptionValue();
        }

        return array_unique($productOptionIds);
    }
}
