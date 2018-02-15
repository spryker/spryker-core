<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Business;

use Spryker\Zed\CmsPageSearch\Business\Search\CmsPageSearchWriter;
use Spryker\Zed\CmsPageSearch\CmsPageSearchDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsPageSearch\CmsPageSearchConfig getConfig()
 * @method \Spryker\Zed\CmsPageSearch\Persistence\CmsPageSearchQueryContainerInterface getQueryContainer()
 */
class CmsPageSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsPageSearch\Business\Search\CmsPageSearchWriterInterface
     */
    public function createCmsPageSearchWriter()
    {
        return new CmsPageSearchWriter(
            $this->getQueryContainer(),
            $this->getCmsFacade(),
            $this->getSearchFacade(),
            $this->getUtilEncoding(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\CmsPageSearch\Dependency\Service\CmsPageSearchToUtilEncodingInterface
     */
    protected function getUtilEncoding()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToCmsInterface
     */
    protected function getCmsFacade()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::FACADE_CMS);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToSearchInterface
     */
    protected function getSearchFacade()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::FACADE_SEARCH);
    }
}
