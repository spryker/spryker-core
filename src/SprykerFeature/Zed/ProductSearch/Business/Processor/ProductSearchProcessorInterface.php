<?php
namespace SprykerFeature\Zed\ProductSearch\Business\Processor;


/**
 * Class ProductSearchProcessor
 *
 * @package SprykerFeature\Zed\ProductSearch\Business\Processor
 */
interface ProductSearchProcessorInterface
{
    /**
     * @param array $productsRaw
     * @param array $processedProducts
     * @param string $locale
     *
     * @return array
     */
    public function buildProducts(array $productsRaw, array $processedProducts, $locale);
}