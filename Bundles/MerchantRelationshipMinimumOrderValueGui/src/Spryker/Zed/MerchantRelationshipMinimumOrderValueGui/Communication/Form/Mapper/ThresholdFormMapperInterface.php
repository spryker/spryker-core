<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\Mapper;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;

interface ThresholdFormMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function map(array $data, MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer): MerchantRelationshipMinimumOrderValueTransfer;
}
