<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Communication;

use Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchDependencyProvider;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Business\ConfigurableBundlePageSearchFacadeInterface getFacade()
 */
class ConfigurableBundlePageSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ConfigurableBundlePageSearchToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplatePageMapExpanderPluginInterface>
     */
    public function getConfigurableBundleTemplatePageMapExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_MAP_EXPANDER);
    }
}
