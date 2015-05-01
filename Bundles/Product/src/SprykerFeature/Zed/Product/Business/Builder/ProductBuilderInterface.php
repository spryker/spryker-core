<?php

namespace SprykerFeature\Zed\Product\Business\Builder;

use SprykerFeature\Shared\Product\Model\ProductInterface;

/**
 * Class ProductBuilder
 *
 * @package SprykerFeature\Zed\Product\Business\Builder
 */
interface ProductBuilderInterface
{
    /**
     * @param array $data
     *
     * @return ProductInterface
     */
    public function buildProduct(array $data);
}