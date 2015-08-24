<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ProductCategory\Service;

use Generated\Client\Ide\FactoryAutoCompletion\ProductCategoryService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\ProductCategory\ProductCategoryDependencyProvider;
use SprykerFeature\Client\ProductCategory\Service\Zed\ProductCategoryStubInterface;

/**
 * @method ProductCategoryService getFactory()
 */
class ProductCategoryDependencyContainer extends AbstractServiceDependencyContainer
{
    /**
     * @return ProductCategoryStubInterface
     */
    public function createZedProductCategoryStub()
    {
        return $this->getFactory()->createZedProductCategoryStub(
            $this->getProvidedDependency(ProductCategoryDependencyProvider::SERVICE_ZED)
        );
    }
}
