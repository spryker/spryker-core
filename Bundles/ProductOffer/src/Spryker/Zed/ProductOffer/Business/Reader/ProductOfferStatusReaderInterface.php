<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Reader;

interface ProductOfferStatusReaderInterface
{
    /**
     * @param string $currentStatus
     *
     * @return string[]
     */
    public function getApplicableApprovalStatuses(string $currentStatus): array;
}
