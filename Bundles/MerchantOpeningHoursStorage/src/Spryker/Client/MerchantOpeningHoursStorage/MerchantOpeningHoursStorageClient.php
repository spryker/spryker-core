<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantOpeningHoursStorage;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageFactory getFactory()
 */
class MerchantOpeningHoursStorageClient extends AbstractClient implements MerchantOpeningHoursStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer|null
     */
    public function findMerchantOpeningHoursByIdMerchant(int $idMerchant): ?MerchantOpeningHoursStorageTransfer
    {
        return $this->getFactory()
            ->createMerchantOpeningHoursStorageReader()
            ->findMerchantOpeningHoursByIdMerchant($idMerchant);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[]
     */
    public function getMerchantOpeningHoursByMerchantIds(array $merchantIds): array
    {
        return $this->getFactory()
            ->createMerchantOpeningHoursStorageReader()
            ->getMerchantOpeningHoursByMerchantIds($merchantIds);
    }
}
