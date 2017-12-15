<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductPageSearch\Business\Attribute\ProductPageAttribute;
use Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapper;
use Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriter;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisher;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainer getQueryContainer()
 */
class ProductPageSearchBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface
     */
    public function createProductAbstractPagePublisher()
    {
        return new ProductAbstractPagePublisher(
            $this->getQueryContainer(),
            $this->getProductPageDataExpanderPlugins(),
            $this->createProductPageMapper(),
            $this->createProductPageWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface
     */
    public function createProductPageMapper()
    {
        return new ProductPageSearchMapper(
            $this->createProductPageAttribute(),
            $this->getSearchFacade(),
            $this->getUtilEncoding(),
            $this->getStore()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Attribute\ProductPageAttributeInterface
     */
    public function createProductPageAttribute()
    {
        return new ProductPageAttribute(
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface
     */
    public function createProductPageWriter()
    {
        return new ProductPageSearchWriter(
            $this->getUtilEncoding()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface
     */
    public function getSearchFacade()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[]
     */
    public function getProductPageDataExpanderPlugins()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PLUGIN_PRODUCT_PAGE_DATA_EXPANDER);
    }

}
