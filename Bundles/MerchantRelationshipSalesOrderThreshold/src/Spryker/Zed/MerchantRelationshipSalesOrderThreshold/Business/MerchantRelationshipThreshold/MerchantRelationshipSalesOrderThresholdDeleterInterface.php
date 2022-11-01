<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer;

interface MerchantRelationshipSalesOrderThresholdDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer
     */
    public function deleteMerchantRelationshipSalesOrderThresholdCollection(
        MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
    ): MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer;
}
