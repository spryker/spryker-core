<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Processor;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class ProductSearchProcessor implements ProductSearchProcessorInterface
{

    /**
     * @var string
     */
    protected $storeName = '';

    /**
     * @var \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    private $keyBuilder;

    /**
     * @param \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param string $storeName
     */
    public function __construct(
        KeyBuilderInterface $keyBuilder,
        $storeName = ''
    ) {
        $this->storeName = $storeName;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param array $productsRaw
     * @param array $processedProducts
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    public function buildProducts(array $productsRaw, array $processedProducts, LocaleTransfer $locale)
    {
        foreach ($productsRaw as $index => $productData) {
            $productKey = $this->keyBuilder->generateKey($productData['id_product_abstract'], $locale->getLocaleName());
            $processedProducts[$productKey] = $this->buildBaseProduct($productData, $locale);
        }

        return $processedProducts;
    }

    /**
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    protected function buildBaseProduct(array $productData, LocaleTransfer $locale)
    {
        $productUrls = explode(',', $productData['product_urls']);

        return [
            PageIndexMap::SEARCH_RESULT_DATA => [
                'id_product_abstract' => $productData['id_product_abstract'],
                'abstract_sku' => $productData['abstract_sku'],
                'abstract_name' => $productData['abstract_name'],
                'url' => $productUrls[0],
            ],
            PageIndexMap::FULL_TEXT_BOOSTED => [
                $productData['abstract_name'],
            ],
            PageIndexMap::FULL_TEXT => [
                $productData['abstract_name'],
            ],
            PageIndexMap::SUGGESTION_TERMS => [
                $productData['abstract_name'],
            ],
            PageIndexMap::COMPLETION_TERMS => [
                $productData['abstract_name'],
            ],
            PageIndexMap::STRING_SORT => [
                'name' => $productData['abstract_name'],
            ],
            PageIndexMap::STORE => $this->storeName,
            PageIndexMap::LOCALE => $locale->getLocaleName(),
        ];
    }

}
