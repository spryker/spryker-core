<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class ProductSearchProcessor implements ProductSearchProcessorInterface
{

    /**
     * @var string
     */
    protected $storeName = '';

    /**
     * @var KeyBuilderInterface
     */
    private $keyBuilder;

    /**
     * @param KeyBuilderInterface $keyBuilder
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
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildProducts(array $productsRaw, array $processedProducts, LocaleTransfer $locale)
    {
        foreach ($productsRaw as $index => $productData) {
            $productKey = $this->keyBuilder->generateKey($productData['id_abstract_product'], $locale->getLocaleName());
            $processedProducts[$productKey] = $this->buildBaseProduct($productData, $locale);
        }

        return $processedProducts;
    }

    /**
     * @param array $productData
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    protected function buildBaseProduct(array $productData, LocaleTransfer $locale)
    {
        $productUrls = explode(',', $productData['product_urls']);

        return [
            'search-result-data' => [
                'id_abstract_product' => $productData['id_abstract_product'],
                'abstract_sku' => $productData['abstract_sku'],
                'abstract_name' => $productData['abstract_name'],
                'url' => $productUrls[0],
            ],
            'full-text-boosted' => [
                $productData['abstract_name'],
            ],
            'full-text' => [
                $productData['abstract_name'],
            ],
            'suggestion-terms' => [
                $productData['abstract_name'],
            ],
            'completion-terms' => [
                $productData['abstract_name'],
            ],
            'string-sort' => [
                'name' => $productData['abstract_name'],
            ],
            'store' => $this->storeName,
            'locale' => $locale->getLocaleName(),
        ];
    }

}
