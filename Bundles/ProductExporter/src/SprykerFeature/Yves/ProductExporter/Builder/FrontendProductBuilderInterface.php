<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\ProductExporter\Builder;

use SprykerFeature\Yves\ProductExporter\Model\AbstractProduct;

/**
 * Interface FrontendProductBuilderInterface
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
