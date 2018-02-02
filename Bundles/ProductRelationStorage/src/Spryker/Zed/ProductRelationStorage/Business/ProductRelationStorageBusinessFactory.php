<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductRelationStorage\Business\Storage\ProductRelationStorageWriter;
use Spryker\Zed\ProductRelationStorage\ProductRelationStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductRelationStorage\ProductRelationStorageConfig getConfig()
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageQueryContainerInterface getQueryContainer()
 */
class ProductRelationStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductRelationStorage\Business\Storage\ProductRelationStorageWriterInterface
     */
    public function createProductRelationStorageWriter()
    {
        return new ProductRelationStorageWriter(
            $this->getQueryContainer(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductRelationStorageDependencyProvider::STORE);
    }
}
