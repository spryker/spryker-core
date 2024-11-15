<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business\InactiveItemsFilter;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToMessengerFacadeInterface;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToStoreFacadeInterface;

class InactiveItemsFilter implements InactiveItemsFilterInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_PARAM_SKU = '%sku%';

    /**
     * @var string
     */
    protected const MESSAGE_INFO_CONCRETE_INACTIVE_PRODUCT_REMOVED = 'product-cart.info.concrete-product-inactive.removed';

    /**
     * @var \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface $productFacade
     * @param \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        ProductCartConnectorToProductInterface $productFacade,
        ProductCartConnectorToStoreFacadeInterface $storeFacade,
        ProductCartConnectorToMessengerFacadeInterface $messengerFacade
    ) {
        $this->productFacade = $productFacade;
        $this->storeFacade = $storeFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $filteredItemTransfers = $this->filterOutInactiveItems(
            $quoteTransfer->getStore(),
            $quoteTransfer->getItems(),
        );

        return $quoteTransfer->setItems($filteredItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function filterOutInactiveCartChangeItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $filteredItemTransfers = $this->filterOutInactiveItems(
            $cartChangeTransfer->getQuoteOrFail()->getStoreOrFail(),
            $cartChangeTransfer->getItems(),
        );

        return $cartChangeTransfer->setItems($filteredItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function filterOutInactiveItems(StoreTransfer $storeTransfer, ArrayObject $itemTransfers): ArrayObject
    {
        $skus = $this->extractProductSkus($itemTransfers);
        if ($skus === []) {
            return $itemTransfers;
        }

        $productCriteriaTransfer = $this->createProductCriteriaTransfer(
            $storeTransfer,
            $skus,
        );

        $productConcreteTransfers = $this->productFacade->getProductConcretesByCriteria($productCriteriaTransfer);
        $indexedProductConcreteTransfers = $this->indexProductConcreteTransfersBySku($productConcreteTransfers);

        $filteredItemTransfers = new ArrayObject();
        $messageTransfersIndexedBySku = [];
        foreach ($itemTransfers as $key => $itemTransfer) {
            if (!isset($indexedProductConcreteTransfers[$itemTransfer->getSku()])) {
                $this->addFilterMessage($itemTransfer->getSku(), $messageTransfersIndexedBySku);

                continue;
            }

            $filteredItemTransfers->offsetSet($key, $itemTransfer);
        }

        return $filteredItemTransfers;
    }

    /**
     * @param string $sku
     * @param array<string, \Generated\Shared\Transfer\MessageTransfer> $messageTransfersIndexedBySku
     *
     * @return void
     */
    protected function addFilterMessage(string $sku, array $messageTransfersIndexedBySku): void
    {
        if (isset($messageTransfersIndexedBySku[$sku])) {
            return;
        }

        $messageTransfersIndexedBySku[$sku] = (new MessageTransfer())
            ->setValue(static::MESSAGE_INFO_CONCRETE_INACTIVE_PRODUCT_REMOVED)
            ->setParameters([
                static::MESSAGE_PARAM_SKU => $sku,
            ]);

        $this->messengerFacade->addInfoMessage($messageTransfersIndexedBySku[$sku]);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string>
     */
    protected function extractProductSkus(ArrayObject $itemTransfers): array
    {
        $skus = [];
        foreach ($itemTransfers as $itemTransfer) {
            $skus[] = $itemTransfer->getSku();
        }

        return $skus;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function indexProductConcreteTransfersBySku(array $productConcreteTransfers): array
    {
        $indexedProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $indexedProductConcreteTransfers[$productConcreteTransfer->getSku()] = $productConcreteTransfer;
        }

        return $indexedProductConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param list<string> $productSkus
     *
     * @return \Generated\Shared\Transfer\ProductCriteriaTransfer
     */
    protected function createProductCriteriaTransfer(StoreTransfer $storeTransfer, array $productSkus): ProductCriteriaTransfer
    {
        return (new ProductCriteriaTransfer())
            ->setSkus($productSkus)
            ->setIsActive(true)
            ->setIdStore(
                $this->storeFacade->getStoreByName($storeTransfer->getName())->getIdStore(),
            );
    }
}
