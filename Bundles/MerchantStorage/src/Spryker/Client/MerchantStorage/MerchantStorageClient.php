<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\MerchantStorage\MerchantStorageFactory getFactory()
 */
class MerchantStorageClient extends AbstractClient implements MerchantStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    public function mapMerchantStorageData(array $data): MerchantStorageTransfer
    {
        return $this->getFactory()
            ->createMerchantStorageMapper()
            ->mapMerchantStorageDataToMerchantStorageTransfer($data);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOne(MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer): ?MerchantStorageTransfer
    {
        return $this->getFactory()
            ->createMerchantStorageReader()
            ->findOne($merchantStorageCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function get(MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer): array
    {
        return $this->getFactory()
            ->createMerchantStorageReader()
            ->get($merchantStorageCriteriaTransfer);
    }
}
