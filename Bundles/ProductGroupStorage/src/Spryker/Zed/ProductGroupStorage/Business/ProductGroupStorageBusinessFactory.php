<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductGroupStorage\Business\Storage\ProductAbstractGroupStorageWriter;
use Spryker\Zed\ProductGroupStorage\ProductGroupStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductGroupStorage\ProductGroupStorageConfig getConfig()
 * @method \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainerInterface getQueryContainer()
 */
class ProductGroupStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductGroupStorage\Business\Storage\ProductAbstractGroupStorageWriterInterface
     */
    public function createProductGroupStorageWriter()
    {
        return new ProductAbstractGroupStorageWriter(
            $this->getQueryContainer(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(ProductGroupStorageDependencyProvider::STORE);
    }
}
