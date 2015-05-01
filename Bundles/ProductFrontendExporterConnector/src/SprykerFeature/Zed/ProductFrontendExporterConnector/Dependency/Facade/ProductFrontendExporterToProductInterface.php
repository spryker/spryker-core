<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Dependency\Facade;

/**
 * Interface ProductFrontendExporterToProductInterface
 *
 * @package SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Builder
 */
interface ProductFrontendExporterToProductInterface
{
    /**
     * @param array $productsData
     *
     * @return array
     */
    public function buildProducts(array $productsData);
}
