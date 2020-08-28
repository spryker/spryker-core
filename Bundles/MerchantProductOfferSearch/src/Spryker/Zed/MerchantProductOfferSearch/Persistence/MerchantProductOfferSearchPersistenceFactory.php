<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchDependencyProvider;
use Spryker\Zed\MerchantProductOfferSearch\Persistence\Mapper\ProductAbstractMerchantMapper;
use Spryker\Zed\MerchantProductOfferSearch\Persistence\Mapper\ProductAbstractMerchantMapperInterface;

/**
 * @method \Spryker\Zed\MerchantProductOfferSearch\MerchantProductOfferSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface getRepository()
 */
class MerchantProductOfferSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOfferSearch\Persistence\Mapper\ProductAbstractMerchantMapperInterface
     */
    public function createProductAbstractMerchantMapper(): ProductAbstractMerchantMapperInterface
    {
        return new ProductAbstractMerchantMapper();
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return $this->getProvidedDependency(MerchantProductOfferSearchDependencyProvider::PROPEL_QUERY_PRODUCT_OFFER);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractPropelQuery(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(MerchantProductOfferSearchDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }
}
