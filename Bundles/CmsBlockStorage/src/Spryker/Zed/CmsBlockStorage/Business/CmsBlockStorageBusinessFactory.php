<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Business;

use Spryker\Zed\CmsBlockStorage\Business\Storage\CmsBlockStorageWriter;
use Spryker\Zed\CmsBlockStorage\CmsBlockStorageDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsBlockStorage\CmsBlockStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface getQueryContainer()
 */
class CmsBlockStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsBlockStorage\Business\Storage\CmsBlockStorageWriter
     */
    public function createCmsBlockStorageWriter()
    {
        return new CmsBlockStorageWriter(
            $this->getQueryContainer(),
            $this->getUtilSanitize(),
            $this->getContentWidgetDataExpanderPlugins(),
            $this->getStore(),
            $this->getConfig()->isSendingToQueue()
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilSanitizeServiceInterface
     */
    protected function getUtilSanitize()
    {
        return $this->getProvidedDependency(CmsBlockStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\CmsBlockStorage\Dependency\Plugin\CmsBlockStorageDataExpanderPluginInterface[]
     */
    protected function getContentWidgetDataExpanderPlugins()
    {
        return $this->getProvidedDependency(CmsBlockStorageDependencyProvider::PLUGIN_CONTENT_WIDGET_DATA_EXPANDER);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(CmsBlockStorageDependencyProvider::STORE);
    }
}
