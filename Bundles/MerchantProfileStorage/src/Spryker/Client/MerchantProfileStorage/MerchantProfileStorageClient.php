<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\MerchantProfileStorage\MerchantProfileStorageFactory getFactory()
 */
class MerchantProfileStorageClient extends AbstractClient implements MerchantProfileStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer
     */
    public function mapMerchantProfileStorageData(array $data): MerchantProfileStorageTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileStorageMapper()
            ->mapMerchantProfileStorageDataToMerchantProfileStorageTransfer($data);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer|null
     */
    public function findMerchantProfileStorageData(int $idMerchant): ?MerchantProfileStorageTransfer
    {
        return $this->getFactory()
            ->createMerchantProfileStorageReader()
            ->findMerchantProfileStorageData($idMerchant);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer[]
     */
    public function findMerchantProfileStorageList(array $merchantIds): array
    {
        return $this->getFactory()
            ->createMerchantProfileStorageReader()
            ->findMerchantProfileStorageList($merchantIds);
    }
}
