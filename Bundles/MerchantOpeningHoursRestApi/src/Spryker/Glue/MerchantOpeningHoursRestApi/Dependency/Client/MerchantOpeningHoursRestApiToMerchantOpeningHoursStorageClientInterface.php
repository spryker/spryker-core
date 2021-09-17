<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client;

interface MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface
{
    /**
     * @param array<int> $merchantIds
     *
     * @return array<\Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer>
     */
    public function getMerchantOpeningHoursByMerchantIds(array $merchantIds): array;
}
