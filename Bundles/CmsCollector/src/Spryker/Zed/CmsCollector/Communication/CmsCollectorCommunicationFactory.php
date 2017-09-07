<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Communication;

use Spryker\Zed\CmsCollector\CmsCollectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsCollector\CmsCollectorConfig getConfig()
 */
class CmsCollectorCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\CmsCollector\Dependency\Plugin\CmsPageCollectorDataExpanderPluginInterface[]
     */
    public function getCollectorDataExpanderPlugins()
    {
        return $this->getProvidedDependency(CmsCollectorDependencyProvider::COLLECTOR_DATA_EXPANDER_PLUGINS);
    }

}
