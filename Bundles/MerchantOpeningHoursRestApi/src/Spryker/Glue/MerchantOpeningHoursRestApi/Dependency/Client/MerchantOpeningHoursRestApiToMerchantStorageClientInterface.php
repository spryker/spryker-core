<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client;

interface MerchantOpeningHoursRestApiToMerchantStorageClientInterface
{
    /**
     * @param string[] $merchantReferences
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function findByMerchantReference(array $merchantReferences): array;
}
