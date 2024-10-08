<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AnalyticsGui\Communication;

use Spryker\Zed\AnalyticsGui\AnalyticsGuiDependencyProvider;
use Spryker\Zed\AnalyticsGui\Dependency\Facade\AnalyticsGuiToUserFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class AnalyticsGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\AnalyticsGui\Dependency\Facade\AnalyticsGuiToUserFacadeInterface
     */
    public function getUserFacade(): AnalyticsGuiToUserFacadeInterface
    {
        return $this->getProvidedDependency(AnalyticsGuiDependencyProvider::FACADE_USER);
    }

    /**
     * @return list<\Spryker\Zed\AnalyticsGuiExtension\Dependency\Plugin\AnalyticsCollectionExpanderPluginInterface>
     */
    public function getAnalyticsCollectionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(AnalyticsGuiDependencyProvider::PLUGINS_ANALYTICS_COLLECTION_EXPANDER);
    }
}
