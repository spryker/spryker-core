<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DiscountPromotion\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\DiscountPromotion\Dependency\Client\DiscountPromotionToProductInterface;
use Spryker\Yves\DiscountPromotion\Dependency\StorageProductMapperPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class PromotionProductMapper implements PromotionProductMapperInterface
{

    /**
     * @var \Spryker\Yves\DiscountPromotion\Dependency\Client\DiscountPromotionToProductInterface
     */
    protected $productClient;

    /**
     * @var \Spryker\Yves\DiscountPromotion\Dependency\StorageProductMapperPluginInterface
     */
    protected $storageProductMapperPlugin;

    /**
     * @param \Spryker\Yves\DiscountPromotion\Dependency\Client\DiscountPromotionToProductInterface $productClient
     * @param \Spryker\Yves\DiscountPromotion\Dependency\StorageProductMapperPluginInterface $storageProductMapperPlugin
     */
    public function __construct(
        DiscountPromotionToProductInterface $productClient,
        StorageProductMapperPluginInterface $storageProductMapperPlugin
    ) {

        $this->productClient = $productClient;
        $this->storageProductMapperPlugin = $storageProductMapperPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer[]
     */
    public function mapPromotionItemsFromProductStorage(QuoteTransfer $quoteTransfer, Request $request)
    {
        $promotionProducts = [];
        foreach ($quoteTransfer->getPromotionItems() as $itemTransfer) {
            $itemTransfer->requireAbstractSku();

            $rawProductData = $this->getProductDataFromStorage($itemTransfer);

            $selectedAttributes = $this->getSelectedAttributes($request, $itemTransfer->getAbstractSku());
            $storageProductTransfer = $this->mapStorageProductTransfer($rawProductData, $selectedAttributes, $itemTransfer);

            $promotionProducts[$itemTransfer->getAbstractSku()] = $storageProductTransfer;
        }

        return $promotionProducts;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $abstractSku
     *
     * @return array
     */
    protected function getSelectedAttributes(Request $request, $abstractSku)
    {
        $selectedAttributes = array_filter($request->query->get('attributes', []));
        return isset($selectedAttributes[$abstractSku]) ? $selectedAttributes[$abstractSku] : [];
    }

    /**
     * @param array $data
     * @param array $selectedAttributes
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    protected function mapStorageProductTransfer(array $data, array $selectedAttributes, ItemTransfer $itemTransfer)
    {
        $storageProductTransfer = $this->storageProductMapperPlugin->mapStorageProduct($data, $selectedAttributes);
        $storageProductTransfer->setMaxQuantity($itemTransfer->getMaxQuantity());

        return $storageProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function getProductDataFromStorage(ItemTransfer $itemTransfer)
    {
        return $this->productClient->getProductAbstractFromStorageByIdForCurrentLocale($itemTransfer->getIdProductAbstract());
    }

}
