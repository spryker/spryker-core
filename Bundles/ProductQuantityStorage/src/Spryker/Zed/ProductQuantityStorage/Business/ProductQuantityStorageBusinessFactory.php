<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductQuantityStorage\Business\Model\ProductQuantityStorageWriter;
use Spryker\Zed\ProductQuantityStorage\ProductQuantityStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductQuantityStorage\ProductQuantityStorageConfig getConfig()
 * @method \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageEntityManagerInterface getEntityManager()
 */
class ProductQuantityStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductQuantityStorage\Business\Model\ProductQuantityStorageWriterInterface
     */
    public function createProductQuantityStorageWriter()
    {
        return new ProductQuantityStorageWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getProductQuantityFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductQuantityStorage\Dependency\Facade\ProductQuantityStorageToProductQuantityFacadeInterface
     */
    public function getProductQuantityFacade()
    {
        return $this->getProvidedDependency(ProductQuantityStorageDependencyProvider::FACADE_PRODUCT_QUANTITY);
    }
}
