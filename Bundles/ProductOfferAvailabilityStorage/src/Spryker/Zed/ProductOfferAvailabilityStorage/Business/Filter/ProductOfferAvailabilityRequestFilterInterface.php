<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business\Filter;

interface ProductOfferAvailabilityRequestFilterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer> $productOfferAvailabilityRequestTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer>
     */
    public function filterOutProductOfferAvailabilityRequestTransfersWithoutStores(array $productOfferAvailabilityRequestTransfers): array;
}
