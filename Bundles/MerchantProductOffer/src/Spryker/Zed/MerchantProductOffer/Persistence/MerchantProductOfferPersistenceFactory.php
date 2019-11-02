<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence;

use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantProductOffer\Persistence\Propel\Mapper\MerchantProductOfferMapper;

/**
 * @method \Spryker\Zed\MerchantProductOffer\MerchantProductOfferConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferRepositoryInterface getRepository()
 */
class MerchantProductOfferPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function createProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantProductOffer\Persistence\Propel\Mapper\MerchantProductOfferMapper
     */
    public function createMerchantProductOfferMapper(): MerchantProductOfferMapper
    {
        return new MerchantProductOfferMapper();
    }
}
