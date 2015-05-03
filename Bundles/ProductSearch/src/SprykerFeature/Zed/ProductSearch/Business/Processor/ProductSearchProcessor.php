<?php

namespace SprykerFeature\Zed\ProductSearch\Business\Processor;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;
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
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function buildProducts(array $productsRaw, array $processedProducts, LocaleDto $locale)
    {
        foreach ($productsRaw as $index => $productData) {
            $productKey = $this->keyBuilder->generateKey($productData['sku'], $locale->getLocaleName());
            $processedProducts[$productKey] = $this->buildBaseProduct($productData, $locale);
        }

        return $processedProducts;
    }

    /**
     * @param array $productData
     * @param LocaleDto $locale
     *
     * @return array
     */
    protected function buildBaseProduct(array $productData, LocaleDto $locale)
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
