<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValue;

interface MerchantRelationshipMinimumOrderValueMapperInterface
{
    /**
     * @param \Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValue $minimumOrderValueEntity
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function mapMerchantRelationshipMinimumOrderValueEntityToTransfer(
        SpyMerchantRelationshipMinimumOrderValue $minimumOrderValueEntity,
        MerchantRelationshipMinimumOrderValueTransfer $minimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer;
}
