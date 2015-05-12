<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Dependency\Facade;

interface ProductFrontendExporterToProductInterface
{
    /**
     * @param array $productsData
     *
     * @return array
     */
    public function buildProducts(array $productsData);
}
