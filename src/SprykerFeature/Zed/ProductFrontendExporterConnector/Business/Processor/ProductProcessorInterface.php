<?php
namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Processor;


/**
 * Class ProductProcessor
 *
 * @package SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Processor
 */
interface ProductProcessorInterface
{
    /**
     * @param array  $products
     * @param string $locale
     *
     * @return array
     */
    public function buildProducts(array $products, $locale);
}