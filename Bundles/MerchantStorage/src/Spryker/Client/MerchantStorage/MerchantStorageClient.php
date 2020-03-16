<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantStorage;

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
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer|null
     */
    public function findOne(int $idMerchant): ?MerchantStorageTransfer
    {
        return $this->getFactory()
            ->createMerchantStorageReader()
            ->findMerchantStorageData($idMerchant);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    public function find(array $merchantIds): array
    {
        return $this->getFactory()
            ->createMerchantStorageReader()
            ->findMerchantStorageList($merchantIds);
    }
}
