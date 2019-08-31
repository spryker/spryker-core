<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateTable;
use Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiDependencyProvider;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \SprykerEco\Zed\ConfigurableBundleGui\Persistence\ConfigurableBundleGuiRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\ConfigurableBundleGui\Persistence\ConfigurableBundleGuiEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \SprykerEco\Zed\ConfigurableBundleGui\ConfigurableBundleGuiConfig getConfig()
 */
class ConfigurableBundleGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateTable
     */
    public function createConfigurableBundleTemplateTable(): ConfigurableBundleTemplateTable
    {
        return new ConfigurableBundleTemplateTable(
            $this->getConfigurableBundleTemplatePropelQuery(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    public function getConfigurableBundleTemplatePropelQuery(): SpyConfigurableBundleTemplateQuery
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ConfigurableBundleGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::FACADE_LOCALE);
    }
}
