<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipThreshold;

use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipSalesOrderThresholdMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCriteriaTransfer $merchantRelationshipSalesOrderThresholdCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCriteriaTransfer
     */
    public function mapThresholdCollectionDeleteCriteriaToThresholdCriteria(
        MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer,
        MerchantRelationshipSalesOrderThresholdCriteriaTransfer $merchantRelationshipSalesOrderThresholdCriteriaTransfer
    ): MerchantRelationshipSalesOrderThresholdCriteriaTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionTransfer $merchantRelationshipSalesOrderThresholdCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer
     */
    public function mapThresholdCollectionToThresholdCollectionResponse(
        MerchantRelationshipSalesOrderThresholdCollectionTransfer $merchantRelationshipSalesOrderThresholdCollectionTransfer,
        MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer $merchantRelationshipSalesOrderThresholdCollectionResponseTransfer
    ): MerchantRelationshipSalesOrderThresholdCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer|null $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
     */
    public function mapMerchantRelationshipToDeleteThresholdCollectionCriteria(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ?MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer $merchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer = null
    ): MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer;
}
