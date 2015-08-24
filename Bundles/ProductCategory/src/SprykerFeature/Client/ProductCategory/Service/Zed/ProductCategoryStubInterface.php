<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ProductCategory\Service\Zed;


use Generated\Shared\ProductCategory\ProductCategoryInterface;
use Generated\Shared\Transfer\ProductCategoryTransfer;

interface ProductCategoryStubInterface
{

    /**
     * @param ProductCategoryInterface $productCategoryTransfer
     *
     * @return ProductCategoryTransfer
     */
    public function getProducts(ProductCategoryInterface $productCategoryTransfer);

}
