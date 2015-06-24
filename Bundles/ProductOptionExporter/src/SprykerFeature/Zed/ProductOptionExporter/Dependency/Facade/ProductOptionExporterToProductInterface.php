<?php

namespace SprykerFeature\Zed\ProductOptionExporter\Dependency\Facade;

/**
 * (c) Spryker Systems GmbH copyright protected
 */
interface ProductOptionExporterToProductInterface
{

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getConcreteProductIdBySku($sku);

    /**
     * @param string $sku
     *
     * @return float
     */
    public function getEffectiveTaxRateForConcreteProduct($sku);
}
