<?php

namespace SprykerFeature\Yves\ProductExporter\Builder;

use SprykerFeature\Yves\ProductExporter\Model\AbstractProduct;

/**
 * Interface FrontendProductBuilderInterface
 * @package SprykerFeature\Yves\ProductExport\Builder
 */
interface FrontendProductBuilderInterface
{
    /**
     * @param array $productData
     *
     * @return AbstractProduct
     */
    public function buildProduct(array $productData);
}