<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ProductCategory\Service;

use Generated\Shared\ProductCategory\ProductCategoryInterface;
use Generated\Shared\Transfer\ProductCategoryTransfer;

interface ProductCategoryClientInterface
{

    /**
     * @param ProductCategoryInterface $productCategoryTransfer
     *
     * @return ProductCategoryTransfer
     */
    public function getProducts(ProductCategoryInterface $productCategoryTransfer);

}
