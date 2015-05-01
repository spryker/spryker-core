<?php

namespace SprykerFeature\Yves\ProductExporter\Builder;

use SprykerFeature\Yves\ProductExporter\Model\Product;

/**
 * Interface FrontendProductBuilderInterface
 * @package SprykerFeature\Yves\ProductExport\Builder
 */
interface FrontendProductBuilderInterface
{
    /**
     * @param array $productData
     *
     * @return Product
     */
    public function buildProduct(array $productData);
}