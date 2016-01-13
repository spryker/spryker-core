<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionExporter\Dependency\Facade;

interface ProductOptionExporterToProductInterface
{

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku);

    /**
     * @param string $sku
     *
     * @return float
     */
    public function getEffectiveTaxRateForProductConcrete($sku);

}
