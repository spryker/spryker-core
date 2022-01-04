<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapper;
use Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapperInterface;
use Spryker\Zed\ProductSetPageSearch\Business\Search\ProductSetPageSearchWriter;
use Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToStoreFacadeInterface;
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
            $this->createProductSetSearchDataMapper(),
            $this->getProductSetFacade(),
            $this->getStoreFacade(),
            $this->getConfig()->isSendingToQueue(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Service\ProductSetPageSearchToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductSetInterface
     */
    public function getProductSetFacade()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::FACADE_PRODUCT_SET);
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapperInterface
     */
    public function createProductSetSearchDataMapper(): ProductSetSearchDataMapperInterface
    {
        return new ProductSetSearchDataMapper();
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductSetPageSearchToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::FACADE_STORE);
    }
}
