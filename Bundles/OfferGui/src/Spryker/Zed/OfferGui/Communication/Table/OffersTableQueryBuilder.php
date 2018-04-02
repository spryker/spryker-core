<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Table;

use Orm\Zed\Offer\Persistence\SpyOfferQuery;

class OffersTableQueryBuilder implements OffersTableQueryBuilderInterface
{
    /**
     * @var \Orm\Zed\Offer\Persistence\SpyOfferQuery
     */
    protected $offerQuery;

    /**
     * @param \Orm\Zed\Offer\Persistence\SpyOfferQuery $offerQuery
     */
    public function __construct(SpyOfferQuery $offerQuery)
    {
        $this->offerQuery = $offerQuery;
    }

    /**
     * @return \Orm\Zed\Offer\Persistence\SpyOfferQuery
     */
    public function buildQuery()
    {
        $query = $this->offerQuery;

        return $query;
    }
}
