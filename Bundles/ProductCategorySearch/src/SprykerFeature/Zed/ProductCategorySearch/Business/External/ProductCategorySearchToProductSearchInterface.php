<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategorySearch\Business\External;

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
