<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantTransfer;

class MerchantMapper implements MerchantMapperInterface
{
    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant[] $merchantEntities
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer[]
     */
    public function mapMerchantEntityTransfersToMerchantTransfers(array $merchantEntities, MerchantTransfer $merchantTransfer): array
    {
        $merchantTransfers = [];
        foreach ($merchantEntities as $merchantEntity) {
            $merchantTransfers[] = $merchantTransfer
                ->fromArray($merchantEntity->toArray(), true);
        }

        return $merchantTransfers;
    }
}
