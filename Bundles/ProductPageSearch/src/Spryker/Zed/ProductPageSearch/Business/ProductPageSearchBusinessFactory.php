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
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisher;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisherInterface;
use Spryker\Zed\ProductPageSearch\Persistence\Mapper\ProductPageSearchMapper as ProductPageSearchPersistenceMapper;
use Spryker\Zed\ProductPageSearch\Persistence\Mapper\ProductPageSearchMapperInterface;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchEntityManagerInterface getEntityManager()
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
     * @return \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisherInterface
     */
    public function createProductConcretePageSearchPublisher(): ProductConcretePageSearchPublisherInterface
    {
        return new ProductConcretePageSearchPublisher(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createProductPageSearchPersistenceMapper(),
            $this->getUtilEncoding(),
            $this->getProductConcretePageDataExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface
     */
    protected function createProductPageSearchPersistenceMapper(): ProductPageSearchMapperInterface
    {
        return new ProductPageSearchPersistenceMapper($this->getSearchFacade());
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface
     */
    protected function createProductPageMapper()
    {
        return new ProductPageSearchMapper(
            $this->createProductPageAttribute(),
            $this->getSearchFacade(),
            $this->getUtilEncoding()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Attribute\ProductPageAttributeInterface
     */
    protected function createProductPageAttribute()
    {
        return new ProductPageAttribute(
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface
     */
    protected function createProductPageWriter()
    {
        return new ProductPageSearchWriter(
            $this->getUtilEncoding(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface
     */
    protected function getUtilEncoding()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface
     */
    protected function getSearchFacade()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[]
     */
    protected function getProductPageDataExpanderPlugins()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PLUGIN_PRODUCT_PAGE_DATA_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface[]
     */
    protected function getProductConcretePageDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PLUGINS_PRODUCT_CONCRETE_PAGE_DATA_EXPANDER);
    }
}
