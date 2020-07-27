<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductBundleStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleStorage\ProductBundleStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductBundleStorage\ProductBundleStorageConfig getConfig()
 * @method \Spryker\Zed\ProductBundleStorage\Business\ProductBundleStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageRepositoryInterface getRepository()
 */
class ProductBundleStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductBundleStorage\Dependency\Facade\ProductBundleStorageToProductBundleFacadeInterface
     */
    public function getProductBundleFacade(): ProductBundleStorageToProductBundleFacadeInterface
    {
        return $this->getProvidedDependency(ProductBundleStorageDependencyProvider::FACADE_PRODUCT_BUNDLE);
    }
}
