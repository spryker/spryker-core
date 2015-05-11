<?php

namespace SprykerFeature\Zed\ProductCategorySearch\Business\External;

/**
 * Interface CategoryTreeInterface
 * @package SprykerFeature\Zed\ProductCategory\Business\External
 */
interface ProductCategorySearchToProductSearchInterface
{
    /**
     * @param mixed $data
     * @param string $locale
     *
     * @return mixed
     */
    public function buildProductKey($data, $locale);
}
