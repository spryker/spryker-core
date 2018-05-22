<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence\Mapper;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship;

class MerchantRelationshipMapper implements MerchantRelationshipMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship $spyMerchantRelationship
     *
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship
     */
    public function mapMerchantRelationshipTransferToEntity(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        SpyMerchantRelationship $spyMerchantRelationship
    ): SpyMerchantRelationship {
        $spyMerchantRelationship->fromArray(
            $merchantRelationshipTransfer->modifiedToArray(false)
        );

        return $spyMerchantRelationship;
    }

    /**
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship $spyMerchantRelationship
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function mapEntityToMerchantRelationshipTransfer(
        SpyMerchantRelationship $spyMerchantRelationship,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        return $merchantRelationshipTransfer->fromArray(
            $spyMerchantRelationship->toArray(),
            true
        );
    }
}
