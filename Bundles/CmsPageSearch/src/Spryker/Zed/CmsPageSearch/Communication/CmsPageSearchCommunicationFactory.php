<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Communication;

use Spryker\Zed\CmsPageSearch\CmsPageSearchDependencyProvider;
use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\CmsPageSearch\Dependency\Service\CmsPageSearchToUtilSanitizeServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsPageSearch\Persistence\CmsPageSearchQueryContainer getQueryContainer()
 * @method \Spryker\Zed\CmsPageSearch\CmsPageSearchConfig getConfig()
 */
class CmsPageSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return CmsPageSearchToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return CmsPageSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\CmsPageSearch\Dependency\Service\CmsPageSearchToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToCmsInterface
     */
    public function getCmsFacade()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::FACADE_CMS);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToSearchInterface
     */
    public function getSearchFacade()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::FACADE_SEARCH);
    }

}
