<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductLabelSearch\Business\Mapper\ProductLabelMapper;
use Spryker\Zed\ProductLabelSearch\Business\Mapper\ProductLabelMapperInterface;
use Spryker\Zed\ProductLabelSearch\Business\PageData\ProductPageDataTransferExpander;
use Spryker\Zed\ProductLabelSearch\Business\PageData\ProductPageDataTransferExpanderInterface;
use Spryker\Zed\ProductLabelSearch\Business\Writer\ProductLabelSearchWriter;
use Spryker\Zed\ProductLabelSearch\Business\Writer\ProductLabelSearchWriterInterface;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelInterface;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductPageSearchInterface;
use Spryker\Zed\ProductLabelSearch\ProductLabelSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductLabelSearch\ProductLabelSearchConfig getConfig()
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchQueryContainerInterface getQueryContainer()
 */
class ProductLabelSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductLabelSearch\Business\Writer\ProductLabelSearchWriterInterface
     */
    public function createProductLabelSearchWriter(): ProductLabelSearchWriterInterface
    {
        return new ProductLabelSearchWriter(
            $this->getEventBehaviorFacade(),
            $this->getProductPageSearchFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelSearch\Business\PageData\ProductPageDataTransferExpanderInterface
     */
    public function createProductPageDataTransferExpander(): ProductPageDataTransferExpanderInterface
    {
        return new ProductPageDataTransferExpander(
            $this->createProductLabelMapper(),
            $this->getProductLabelFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabelSearch\Business\Mapper\ProductLabelMapperInterface
     */
    public function createProductLabelMapper(): ProductLabelMapperInterface
    {
        return new ProductLabelMapper();
    }

    /**
     * @return \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ProductLabelSearchToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ProductLabelSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductPageSearchInterface
     */
    public function getProductPageSearchFacade(): ProductLabelSearchToProductPageSearchInterface
    {
        return $this->getProvidedDependency(ProductLabelSearchDependencyProvider::FACADE_PRODUCT_PAGE_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelInterface
     */
    public function getProductLabelFacade(): ProductLabelSearchToProductLabelInterface
    {
        return $this->getProvidedDependency(ProductLabelSearchDependencyProvider::FACADE_PRODUCT_LABEL);
    }
}
