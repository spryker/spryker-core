<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Table;

use Orm\Zed\Offer\Persistence\SpyOffer;
use Orm\Zed\Offer\Persistence\SpyOfferQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

class OffersTableQueryBuilder implements OffersTableQueryBuilderInterface
{
    /**
     * @var SpyOfferQuery
     */
    protected $offerQuery;

    /**
     * @param SpyOfferQuery $offerQuery
     */
    public function __construct(SpyOfferQuery $offerQuery){
        $this->offerQuery = $offerQuery;
    }

    /**
     * @return SpyOfferQuery
     */
    public function buildQuery()
    {
        $query = $this->offerQuery;

        return $query;
    }
}
