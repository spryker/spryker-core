<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Processor;


interface ProductProcessorInterface
{
    /**
     * @param string $locale
     * @param array $products
     *
     * @return array
     */
    public function buildProducts(array $products, $locale);
}