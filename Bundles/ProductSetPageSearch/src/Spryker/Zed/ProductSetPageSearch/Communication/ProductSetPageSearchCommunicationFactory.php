<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Search\ProductSetPageMapPlugin;
use Spryker\Zed\ProductSetPageSearch\ProductSetPageSearchDependencyProvider;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilder;

/**
 * @method \Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSetPageSearch\ProductSetPageSearchConfig getConfig()
 */
class ProductSetPageSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilder
     */
    public function createPageMapBuilder()
    {
        return new PageMapBuilder();
    }

    /**
     * @return \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface
     */
    public function createProductSetPageDataMapPlugin()
    {
        return new ProductSetPageMapPlugin();
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\QueryContainer\ProductSetPageSearchToProductImageQueryContainerBridge
     */
    public function getProductImageQueryContainer()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::QUERY_CONTAINER_PRODUCT_IMAGE);
    }
}
