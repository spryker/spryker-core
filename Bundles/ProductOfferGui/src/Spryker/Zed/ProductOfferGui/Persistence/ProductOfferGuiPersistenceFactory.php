<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductOfferGui\Persistence\Mapper\ProductOfferQueryCriteriaMapper;
use Spryker\Zed\ProductOfferGui\Persistence\Mapper\ProductOfferQueryCriteriaMapperInterface;

/**
 * @method \Spryker\Zed\ProductOfferGui\ProductOfferGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiRepositoryInterface getRepository()
 */
class ProductOfferGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductOfferGui\Persistence\Mapper\ProductOfferQueryCriteriaMapperInterface
     */
    public function createProductOfferQueryCriteriaMapper(): ProductOfferQueryCriteriaMapperInterface
    {
        return new ProductOfferQueryCriteriaMapper();
    }
}
