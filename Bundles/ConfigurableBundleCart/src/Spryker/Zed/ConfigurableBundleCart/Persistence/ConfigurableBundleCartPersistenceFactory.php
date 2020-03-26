<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCart\Persistence;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Spryker\Zed\ConfigurableBundleCart\ConfigurableBundleCartDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundleCart\ConfigurableBundleCartConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundleCart\Persistence\ConfigurableBundleCartRepositoryInterface getRepository()
 */
class ConfigurableBundleCartPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    public function getConfigurableBundleTemplateSlotPropelQuery(): SpyConfigurableBundleTemplateSlotQuery
    {
        return $this->getProvidedDependency(ConfigurableBundleCartDependencyProvider::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT);
    }
}
