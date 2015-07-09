<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

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
