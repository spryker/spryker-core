<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Persistence;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\ConfigurableBundlePageSearch\Persistence\SpyConfigurableBundleTemplatePageSearchQuery;
use Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig getConfig()
 */
class ConfigurableBundlePageSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ConfigurableBundlePageSearch\Persistence\SpyConfigurableBundleTemplatePageSearchQuery
     */
    public function getConfigurableBundlePageSearchQuery(): SpyConfigurableBundleTemplatePageSearchQuery
    {
        return SpyConfigurableBundleTemplatePageSearchQuery::create();
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    public function getConfigurableBundleTemplatePropelQuery(): SpyConfigurableBundleTemplateQuery
    {
        return $this->getProvidedDependency(ConfigurableBundlePageSearchDependencyProvider::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE);
    }
}
