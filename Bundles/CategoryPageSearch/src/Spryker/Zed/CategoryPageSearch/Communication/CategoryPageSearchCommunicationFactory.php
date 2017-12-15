<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Communication;

use Spryker\Zed\CategoryPageSearch\CategoryPageSearchDependencyProvider;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilSanitizeServiceInterface;
use Spryker\Zed\CmsPageSearch\CmsPageSearchDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainer getQueryContainer()
 * @method \Spryker\Zed\CategoryPageSearch\CategoryPageSearchConfig getConfig()
 */
class CategoryPageSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return CategoryPageSearchToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(CategoryPageSearchDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return CategoryPageSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(CategoryPageSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Dependency\Service\CategoryPageSearchToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(CategoryPageSearchDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToSearchInterface
     */
    public function getSearchFacade()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::FACADE_SEARCH);
    }

}
