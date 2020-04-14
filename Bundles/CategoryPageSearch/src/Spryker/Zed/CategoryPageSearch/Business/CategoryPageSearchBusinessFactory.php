<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business;

use Spryker\Zed\CategoryPageSearch\Business\Search\CategoryNodePageSearch;
use Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapper;
use Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapperInterface;
use Spryker\Zed\CategoryPageSearch\CategoryPageSearchDependencyProvider;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToStoreFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryPageSearch\CategoryPageSearchConfig getConfig()
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface getQueryContainer()
 */
class CategoryPageSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Search\CategoryNodePageSearchInterface
     */
    public function createCategoryNodeSearch()
    {
        return new CategoryNodePageSearch(
            $this->getUtilEncoding(),
            $this->createCategoryNodePageSearchDataMapper(),
            $this->getQueryContainer(),
            $this->getStoreFacade(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(CategoryPageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToStoreFacadeInterface
     */
    public function getStoreFacade(): CategoryPageSearchToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CategoryPageSearchDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper\CategoryNodePageSearchDataMapperInterface
     */
    public function createCategoryNodePageSearchDataMapper(): CategoryNodePageSearchDataMapperInterface
    {
        return new CategoryNodePageSearchDataMapper();
    }
}
