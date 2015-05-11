<?php

namespace SprykerFeature\Zed\ProductCategorySearch\Business\External;

/**
 * Interface CategoryTreeInterface
 * @package SprykerFeature\Zed\ProductCategory\Business\External
 */
interface ProductCategorySearchToProductCategoryInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function collectProductNodes(array $data);
}
