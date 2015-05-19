<?php

namespace SprykerFeature\Zed\ProductSearch\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

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
        return [
            'search-result-data' => [
                'sku' => $productData['sku'],
                'name' => $productData['name'],
                'url' => $productData['product_url'],
            ],
            'full-text-boosted' => [
                $productData['name']
            ],
            'full-text' => [
                $productData['name']
            ],
            'suggestion-terms' => [
                $productData['name']
            ],
            'completion-terms' => [
                $productData['name']
            ],
            'string-sort' => [
                'name' => $productData['name']
            ],
            'store' => $this->storeName,
            'locale' => $locale->getLocaleName()
        ];
    }
}
