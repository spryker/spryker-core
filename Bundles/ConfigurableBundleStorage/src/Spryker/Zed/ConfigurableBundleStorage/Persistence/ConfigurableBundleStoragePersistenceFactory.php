<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Persistence;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorageQuery;
use Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface getRepository()
 */
class ConfigurableBundleStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    public function getConfigurableBundleTemplatePropelQuery(): SpyConfigurableBundleTemplateQuery
    {
        return $this->getProvidedDependency(ConfigurableBundleStorageDependencyProvider::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE);
    }

    /**
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorageQuery
     */
    public function getConfigurableBundleTemplateStoragePropelQuery(): SpyConfigurableBundleTemplateStorageQuery
    {
        return SpyConfigurableBundleTemplateStorageQuery::create();
    }
}
