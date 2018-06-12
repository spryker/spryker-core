<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedPublisher\ProductDiscontinuedPublisher;
use Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedPublisher\ProductDiscontinuedPublisherInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedUnpublisher\ProductDiscontinuedUnpublisher;
use Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedUnpublisher\ProductDiscontinuedUnpublisherInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToLocaleFacadeInterface;
use Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig getConfig()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface getRepository()
 */
class ProductDiscontinuedStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedPublisher\ProductDiscontinuedPublisherInterface
     */
    public function createProductDiscontinuedPublisher(): ProductDiscontinuedPublisherInterface
    {
        return new ProductDiscontinuedPublisher(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getProductDiscontinuedFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedUnpublisher\ProductDiscontinuedUnpublisherInterface
     */
    public function createProductDiscontinuedUnpublisher(): ProductDiscontinuedUnpublisherInterface
    {
        return new ProductDiscontinuedUnpublisher(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface
     */
    public function getProductDiscontinuedFacade(): ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedStorageDependencyProvider::FACADE_PRODUCT_DISCONTINUED);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade\ProductDiscontinuedStorageToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductDiscontinuedStorageToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedStorageDependencyProvider::FACADE_LOCALE);
    }
}
