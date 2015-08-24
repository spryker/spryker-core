<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ProductCategory\Service;

use Generated\Shared\ProductCategory\ProductCategoryInterface;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method ProductCategoryDependencyContainer getDependencyContainer()
 */
class ProductCategoryClient extends AbstractClient implements ProductCategoryClientInterface
{

    /**
     * @param ProductCategoryInterface $productCategoryTransfer
     *
     * @return ProductCategoryTransfer
     */
    public function getProducts(ProductCategoryInterface $productCategoryTransfer)
    {
        return $this->getDependencyContainer()
            ->createZedProductCategoryStub()
            ->getProducts($productCategoryTransfer)
        ;
    }
}
