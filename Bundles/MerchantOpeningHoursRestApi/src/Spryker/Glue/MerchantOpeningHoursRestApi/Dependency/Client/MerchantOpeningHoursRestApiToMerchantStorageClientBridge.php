<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;

class MerchantOpeningHoursRestApiToMerchantStorageClientBridge implements MerchantOpeningHoursRestApiToMerchantStorageClientInterface
{
    /**
     * @var \Spryker\Client\MerchantStorage\MerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @param \Spryker\Client\MerchantStorage\MerchantStorageClientInterface $merchantStorageClient
     */
    public function __construct($merchantStorageClient)
    {
        $this->merchantStorageClient = $merchantStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOne(MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer): ?MerchantStorageTransfer
    {
        return $this->merchantStorageClient->findOne($merchantStorageCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\MerchantStorageTransfer>
     */
    public function get(MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer): array
    {
        return $this->merchantStorageClient->get($merchantStorageCriteriaTransfer);
    }
}
