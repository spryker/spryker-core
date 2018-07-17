<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValue;

class MerchantRelationshipMinimumOrderValueMapper implements MerchantRelationshipMinimumOrderValueMapperInterface
{
    /**
     * @param \Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValue $merchantRelationshipMinimumOrderValueEntity
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function mapMerchantRelationshipMinimumOrderValueTransfer(
        SpyMerchantRelationshipMinimumOrderValue $merchantRelationshipMinimumOrderValueEntity,
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $merchantRelationshipMinimumOrderValueTransfer->fromArray($merchantRelationshipMinimumOrderValueEntity->toArray(), true)
            ->setIdMerchantRelationshipMinimumOrderValue($merchantRelationshipMinimumOrderValueEntity->getIdMerchantRelationshipMinOrderValue());

        return $merchantRelationshipMinimumOrderValueTransfer;
    }
}
