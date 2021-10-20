<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelStoreQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductLabel\Persistence\Mapper\LocaleMapper;
use Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelLocalizedAttributesMapper;
use Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelMapper;
use Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelProductAbstractMapper;
use Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelStoreRelationMapper;

/**
 * @method \Spryker\Zed\ProductLabel\ProductLabelConfig getConfig()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelRepositoryInterface getRepository()
 */
class ProductLabelPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function createProductLabelQuery()
    {
        return SpyProductLabelQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function createLocalizedAttributesQuery()
    {
        return SpyProductLabelLocalizedAttributesQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function createProductRelationQuery()
    {
        return SpyProductLabelProductAbstractQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelMapper
     */
    public function createProductLabelMapper(): ProductLabelMapper
    {
        return new ProductLabelMapper(
            $this->createProductLabelStoreRelationMapper(),
            $this->createProductLabelLocalizedAttributesMapper(),
            $this->createProductLabelProductAbstractMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelStoreRelationMapper
     */
    public function createProductLabelStoreRelationMapper(): ProductLabelStoreRelationMapper
    {
        return new ProductLabelStoreRelationMapper();
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelStoreQuery
     */
    public function createProductLabelStoreQuery(): SpyProductLabelStoreQuery
    {
        return SpyProductLabelStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelLocalizedAttributesMapper
     */
    public function createProductLabelLocalizedAttributesMapper(): ProductLabelLocalizedAttributesMapper
    {
        return new ProductLabelLocalizedAttributesMapper($this->createLocaleMapper());
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Persistence\Mapper\LocaleMapper
     */
    public function createLocaleMapper(): LocaleMapper
    {
        return new LocaleMapper();
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Persistence\Mapper\ProductLabelProductAbstractMapper
     */
    public function createProductLabelProductAbstractMapper(): ProductLabelProductAbstractMapper
    {
        return new ProductLabelProductAbstractMapper();
    }
}
