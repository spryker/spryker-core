<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\SpyMerchantEntityTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;

class MerchantMapper implements MerchantMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $spyMerchant
     *
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchant
     */
    public function mapMerchantTransferToMerchantEntity(
        MerchantTransfer $merchantTransfer,
        SpyMerchant $spyMerchant
    ): SpyMerchant {
        $spyMerchant->fromArray(
            $merchantTransfer->modifiedToArray(false)
        );

        return $spyMerchant;
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant $spyMerchant
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function mapMerchantEntityToMerchantTransfer(
        SpyMerchant $spyMerchant,
        MerchantTransfer $merchantTransfer
    ): MerchantTransfer {
        $merchantTransfer = $merchantTransfer->fromArray(
            $spyMerchant->toArray(),
            true
        );

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyMerchantEntityTransfer $merchantEntityTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function mapMerchantEntityTransferToMerchantTransfer(SpyMerchantEntityTransfer $merchantEntityTransfer): MerchantTransfer
    {
        $merchantTransfer = (new MerchantTransfer())
            ->fromArray($merchantEntityTransfer->toArray(), true);

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyMerchantEntityTransfer[] $collection
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function mapMerchantCollectionToMerchantCollectionTransfer(
        $collection,
        MerchantCollectionTransfer $merchantCollectionTransfer
    ): MerchantCollectionTransfer {
        $merchants = new ArrayObject();

        foreach ($collection as $merchantEntityTransfer) {
            $merchants->append($this->mapMerchantEntityTransferToMerchantTransfer($merchantEntityTransfer));
        }

        $merchantCollectionTransfer->setMerchants($merchants);

        return $merchantCollectionTransfer;
    }
}
