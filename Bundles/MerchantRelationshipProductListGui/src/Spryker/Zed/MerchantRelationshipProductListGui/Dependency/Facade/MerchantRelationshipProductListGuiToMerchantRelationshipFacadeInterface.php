<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getMerchantRelationshipCollection(MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer;
}
