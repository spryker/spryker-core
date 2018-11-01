<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Persistence;

use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PriceProductMerchantRelationship\Persistence\Propel\PriceDimensionQueryExpander\MerchantRelationshipPriceQueryExpander;
use Spryker\Zed\PriceProductMerchantRelationship\Persistence\Propel\PriceDimensionQueryExpander\MerchantRelationshipPriceQueryExpanderInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig getConfig()
 */
class PriceProductMerchantRelationshipPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\PriceProductMerchantRelationship\Persistence\Propel\PriceDimensionQueryExpander\MerchantRelationshipPriceQueryExpanderInterface
     */
    public function createMerchantRelationshipPriceQueryExpander(): MerchantRelationshipPriceQueryExpanderInterface
    {
        return new MerchantRelationshipPriceQueryExpander();
    }

    /**
     * @return \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery
     */
    public function createPriceProductMerchantRelationshipQuery(): SpyPriceProductMerchantRelationshipQuery
    {
        return SpyPriceProductMerchantRelationshipQuery::create();
    }
}
