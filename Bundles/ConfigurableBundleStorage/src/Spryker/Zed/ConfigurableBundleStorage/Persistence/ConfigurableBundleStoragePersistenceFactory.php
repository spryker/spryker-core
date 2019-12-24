<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Persistence;

use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorageQuery;
use Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface getRepository()
 */
class ConfigurableBundleStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateStorageQuery
     */
    public function getConfigurableBundleTemplateStoragePropelQuery(): SpyConfigurableBundleTemplateStorageQuery
    {
        return SpyConfigurableBundleTemplateStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ConfigurableBundleStorage\Persistence\SpyConfigurableBundleTemplateImageStorageQuery
     */
    public function getConfigurableBundleTemplateImageStoragePropelQuery(): SpyConfigurableBundleTemplateImageStorageQuery
    {
        return SpyConfigurableBundleTemplateImageStorageQuery::create();
    }
}
