<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Dependency\Facade\ProductFrontendExporterToProductInterface;

class ProductProcessor implements ProductProcessorInterface
{

    /**
     * @var ProductFrontendExporterToProductInterface
     */
    protected $productBuilder;
    /**
     * @var KeyBuilderInterface
     */
    private $productKeyGenerator;

    /**
     * @param ProductFrontendExporterToProductInterface $productBuilder
     * @param KeyBuilderInterface $productKeyBuilder
     */
    public function __construct(
        ProductFrontendExporterToProductInterface $productBuilder,
        KeyBuilderInterface $productKeyBuilder
    ) {
        $this->productBuilder = $productBuilder;
        $this->productKeyGenerator = $productKeyBuilder;
    }

    /**
     * @param array $products
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildProducts(array $products, LocaleTransfer $locale)
    {
        $products = $this->productBuilder->buildProducts($products);

        $exportChunk = [];

        foreach ($products as $index => $productData) {
            $productKey = $this->productKeyGenerator->generateKey(
                $productData['id_abstract_product'],
                $locale->getLocaleName()
            );
            $exportChunk[$productKey] = $this->filterProductData($productData);
        }

        return $exportChunk;
    }

    /**
     * @param array $productData
     *
     * @return array
     */
    protected function filterProductData(array $productData)
    {
        // @TODO get this from the settings, instead of hardcoding it
        $allowedFields = [
            'abstract_sku',
            'abstract_attributes',
            'abstract_name',
            'url',
            'concrete_products',
        ];

        return array_intersect_key($productData, array_flip($allowedFields));
    }

}
