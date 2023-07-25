<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Business\Builder;

interface ProductOfferAvailabilityRequestBuilderInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer> $productOfferAvailabilityRequestTransfers
     * @param array<int, list<int>> $storeIdsGroupedByIdStock
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer>
     */
    public function buildProductOfferAvailabilityRequestsWithStore(
        array $productOfferAvailabilityRequestTransfers,
        array $storeIdsGroupedByIdStock
    ): array;
}
