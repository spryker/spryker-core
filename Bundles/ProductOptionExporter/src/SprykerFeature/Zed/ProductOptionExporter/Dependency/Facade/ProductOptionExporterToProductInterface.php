<?php

namespace SprykerFeature\Zed\ProductOptionExporter\Dependency\Facade;

interface ProductOptionExporterToProductInterface
{

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getConcreteProductIdBySku($sku);
}
