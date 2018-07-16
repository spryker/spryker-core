<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCategoryFilterStorage\Business\Storage\ProductCategoryFilterStorageWriter;
use Spryker\Zed\ProductCategoryFilterStorage\ProductCategoryFilterStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\ProductCategoryFilterStorageConfig getConfig()
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Persistence\ProductCategoryFilterStorageQueryContainerInterface getQueryContainer()
 */
class ProductCategoryFilterStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductCategoryFilterStorage\Business\Storage\ProductCategoryFilterStorageWriterInterface
     */
    public function createProductCategoryFilterStorageWriter()
    {
        return new ProductCategoryFilterStorageWriter(
            $this->getQueryContainer(),
            $this->getUtilEncoding(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\ProductCategoryFilterStorage\Dependency\Service\ProductCategoryFilterStorageToUtilEncodingInterface
     */
    protected function getUtilEncoding()
    {
        return $this->getProvidedDependency(ProductCategoryFilterStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
