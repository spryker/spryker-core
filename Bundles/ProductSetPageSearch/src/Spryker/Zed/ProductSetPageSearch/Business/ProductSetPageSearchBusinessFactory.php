<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSetPageSearch\Business\Search\ProductSetPageSearchWriter;
use Spryker\Zed\ProductSetPageSearch\ProductSetPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSetPageSearch\ProductSetPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainerInterface getQueryContainer()
 */
class ProductSetPageSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Business\Search\ProductSetPageSearchWriterInterface
     */
    public function createProductSetPageSearchWriter()
    {
        return new ProductSetPageSearchWriter(
            $this->getQueryContainer(),
            $this->getUtilEncoding(),
            $this->getSearchFacade(),
            $this->getProductSetFacade(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Service\ProductSetPageSearchToUtilEncodingInterface
     */
    protected function getUtilEncoding()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToSearchInterface
     */
    protected function getSearchFacade()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::FACADE_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductSetInterface
     */
    protected function getProductSetFacade()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::FACADE_PRODUCT_SET);
    }
}
