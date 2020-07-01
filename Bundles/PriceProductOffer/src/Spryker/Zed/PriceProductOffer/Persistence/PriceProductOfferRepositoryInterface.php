<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\QueryCriteriaTransfer;

interface PriceProductOfferRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function createQueryCriteriaTransfer(): QueryCriteriaTransfer;

    /**
     * @param int $idProductOffer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function getPrices(int $idProductOffer): ArrayObject;
}
