<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionConfigurationPresetQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionConfigurationPresetValueQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeTranslationQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageExclusionQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueTranslationQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageConstraintQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery;
use Orm\Zed\Tax\Persistence\SpyTaxSetQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 */
class ProductOptionPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeQuery
     */
    public function createProductOptionTypeQuery()
    {
        return SpyProductOptionTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeTranslationQuery
     */
    public function createProductOptionTypeTranslationQuery()
    {
        return SpyProductOptionTypeTranslationQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery
     */
    public function createProductOptionTypeUsageQuery()
    {
        return SpyProductOptionTypeUsageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function createProductOptionValueQuery()
    {
        return SpyProductOptionValueQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueTranslationQuery
     */
    public function createProductOptionValueTranslationQuery()
    {
        return SpyProductOptionValueTranslationQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery
     */
    public function createProductOptionValueUsageQuery()
    {
        return SpyProductOptionValueUsageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageExclusionQuery
     */
    public function createProductOptionTypeUsageExclusionQuery()
    {
        return SpyProductOptionTypeUsageExclusionQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageConstraintQuery
     */
    public function createProductOptionValueUsageConstraintQuery()
    {
        return SpyProductOptionValueUsageConstraintQuery::create();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function createProductAbstractQuery()
    {
        return SpyProductAbstractQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionConfigurationPresetQuery
     */
    public function createProductOptionConfigurationPresetQuery()
    {
        return SpyProductOptionConfigurationPresetQuery::create();
    }

    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function createTaxSetQuery()
    {
        return SpyTaxSetQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionConfigurationPresetValueQuery
     */
    public function createProductOptionConfigurationPresetValueQuery()
    {
        return SpyProductOptionConfigurationPresetValueQuery::create();
    }

}
