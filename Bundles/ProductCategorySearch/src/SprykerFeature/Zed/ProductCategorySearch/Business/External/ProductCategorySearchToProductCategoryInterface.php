<?php

namespace SprykerFeature\Zed\ProductCategorySearch\Business\External;

interface ProductCategorySearchToProductCategoryInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function collectProductNodes(array $data);
}
