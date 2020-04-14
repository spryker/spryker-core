<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Persistence;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Spryker\Zed\ConfigurableBundle\ConfigurableBundleDependencyProvider;
use Spryker\Zed\ConfigurableBundle\Persistence\Propel\Mapper\ConfigurableBundleMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundle\ConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface getRepository()
 */
class ConfigurableBundlePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundle\Persistence\Propel\Mapper\ConfigurableBundleMapper
     */
    public function createConfigurableBundleMapper(): ConfigurableBundleMapper
    {
        return new ConfigurableBundleMapper();
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    public function getConfigurableBundleTemplatePropelQuery(): SpyConfigurableBundleTemplateQuery
    {
        return SpyConfigurableBundleTemplateQuery::create();
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    public function getConfigurableBundleTemplateSlotPropelQuery(): SpyConfigurableBundleTemplateSlotQuery
    {
        return SpyConfigurableBundleTemplateSlotQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function getProductImageSetQuery(): SpyProductImageSetQuery
    {
        return $this->getProvidedDependency(ConfigurableBundleDependencyProvider::PROPEL_QUERY_PRODUCT_IMAGE_SET);
    }
}
