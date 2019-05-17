<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DiscountPromotion\Mapper;

use Generated\Shared\Transfer\PromotionItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\DiscountPromotion\Dependency\Client\DiscountPromotionToProductInterface;
use Spryker\Yves\DiscountPromotion\Dependency\StorageProductMapperPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class PromotionProductMapper implements PromotionProductMapperInterface
{
    public const URL_PARAM_VARIANT_ATTRIBUTES = 'attributes';

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
        foreach ($quoteTransfer->getPromotionItems() as $promotionItemTransfer) {
            $promotionItemTransfer->requireAbstractSku();

            $rawProductData = $this->getProductDataFromStorage($promotionItemTransfer);
            if (!$rawProductData) {
                continue;
            }

            $selectedAttributes = $this->getSelectedAttributes($request, $promotionItemTransfer->getAbstractSku());
            $storageProductTransfer = $this->mapStorageProductTransfer($rawProductData, $selectedAttributes);

            $storageProductTransfer->setPromotionItem($promotionItemTransfer);

            $promotionProducts[$this->createPromotionProductBucketIdentifier($promotionItemTransfer)] = $storageProductTransfer;
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
        $selectedAttributes = $request->query->get(static::URL_PARAM_VARIANT_ATTRIBUTES, []);

        return isset($selectedAttributes[$abstractSku]) ? $this->filterEmptyAttributes($selectedAttributes[$abstractSku]) : [];
    }

    /**
     * @param array $rawProductData
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    protected function mapStorageProductTransfer(array $rawProductData, array $selectedAttributes)
    {
        return $this->storageProductMapperPlugin->mapStorageProduct($rawProductData, $selectedAttributes);
    }

    /**
     * @param \Generated\Shared\Transfer\PromotionItemTransfer $promotionItemTransfer
     *
     * @return array
     */
    protected function getProductDataFromStorage(PromotionItemTransfer $promotionItemTransfer)
    {
        return $this->productClient->getProductAbstractFromStorageByIdForCurrentLocale($promotionItemTransfer->getIdProductAbstract());
    }

    /**
     * @param array $selectedAttributes
     *
     * @return array
     */
    protected function filterEmptyAttributes(array $selectedAttributes)
    {
        return array_filter($selectedAttributes);
    }

    /**
     * @param \Generated\Shared\Transfer\PromotionItemTransfer $promotionItemTransfer
     *
     * @return string
     */
    protected function createPromotionProductBucketIdentifier(PromotionItemTransfer $promotionItemTransfer)
    {
        return $promotionItemTransfer->getAbstractSku() . '-' . $promotionItemTransfer->getIdDiscountPromotion();
    }
}
