<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getMerchantRelationshipByKey(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer;
}
