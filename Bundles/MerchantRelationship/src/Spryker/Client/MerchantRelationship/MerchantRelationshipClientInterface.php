<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRelationship;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;

interface MerchantRelationshipClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Retrieves merchant relationships filtered by criteria from Persistence.
     * - Uses `MerchantRelationshipCriteriaTransfer.merchantRelationshipConditions.merchantRelationshipIds` to filter by merchant relationship IDs.
     * - Uses `MerchantRelationshipCriteriaTransfer.merchantRelationshipConditions.merchantIds` to filter by merchant IDs.
     * - Uses `MerchantRelationshipCriteriaTransfer.merchantRelationshipConditions.companyIds` to filter by company IDs.
     * - Uses `MerchantRelationshipCriteriaTransfer.merchantRelationshipConditions.ownerCompanyBusinessUnitIds` to filter by owner company business unit IDs.
     * - Uses `MerchantRelationshipCriteriaTransfer.merchantRelationshipConditions.isActiveMerchant` to filter by merchant active status.
     * - Uses `MerchantRelationshipCriteriaTransfer.pagination.{page, maxPerPage}` to paginate results with page and maxPerPage.
     * - Hydrate owner company business unit and merchant.
     * - Executes a stack of {@link \Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipExpanderPluginInterface} plugins.
     * - Returns `MerchantRelationshipCollectionTransfer` filled with found merchant relationships.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function getMerchantRelationshipCollection(
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): MerchantRelationshipCollectionTransfer;
}
