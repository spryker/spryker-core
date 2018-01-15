<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication;

use Spryker\Zed\CmsBlockStorage\CmsBlockStorageDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockStorage\CmsBlockStorageConfig getConfig()
 */
class CmsBlockStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsBlockStorage\Dependency\Facade\CmsBlockStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviourFacade()
    {
        return $this->getProvidedDependency(CmsBlockStorageDependencyProvider::FACADE_EVENT_BEHAVIOUR);
    }

    /**
     * @return \Spryker\Zed\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilSanitizeServiceInterface
     */
    public function getUtilSynchronization()
    {
        return $this->getProvidedDependency(CmsBlockStorageDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\CmsBlockStorage\Dependency\Plugin\CmsBlockStorageDataExpanderPluginInterface[]
     */
    public function getContentWidgetDataExpanderPlugins()
    {
        return $this->getProvidedDependency(CmsBlockStorageDependencyProvider::PLUGIN_CONTENT_WIDGET_DATA_EXPANDER);
    }
}
