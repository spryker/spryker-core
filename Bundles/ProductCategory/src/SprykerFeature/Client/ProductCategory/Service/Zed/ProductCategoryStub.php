<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ProductCategory\Service\Zed;

use Generated\Shared\ProductCategory\ProductCategoryInterface;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;

class ProductCategoryStub implements ProductCategoryStubInterface
{
    /**
     * @var ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param ProductCategoryInterface $productCategoryTransfer
     *
     * @return ProductCategoryTransfer
     */
    public function getProducts(ProductCategoryInterface $productCategoryTransfer)
    {
        return $this->zedStub->call('/product-category/gateway/get-products-by-id-category', $productCategoryTransfer);
    }
}
