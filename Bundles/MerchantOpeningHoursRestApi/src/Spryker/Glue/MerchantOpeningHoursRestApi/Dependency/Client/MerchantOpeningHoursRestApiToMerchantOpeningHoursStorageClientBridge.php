<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client;

class MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientBridge implements MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface
{
    /**
     * @var \Spryker\Client\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageClientInterface
     */
    protected $merchantOpeningHoursStorageClient;

    /**
     * @param \Spryker\Client\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageClientInterface $merchantOpeningHoursStorageClient
     */
    public function __construct($merchantOpeningHoursStorageClient)
    {
        $this->merchantOpeningHoursStorageClient = $merchantOpeningHoursStorageClient;
    }

    /**
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[]
     */
    public function getMerchantOpeningHoursByMerchantIds(array $merchantIds): array
    {
        return $this->merchantOpeningHoursStorageClient->getMerchantOpeningHoursByMerchantIds($merchantIds);
    }
}
