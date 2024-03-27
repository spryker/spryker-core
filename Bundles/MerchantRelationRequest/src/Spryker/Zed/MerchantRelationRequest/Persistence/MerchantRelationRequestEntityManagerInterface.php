<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Persistence;

use Generated\Shared\Transfer\MerchantRelationRequestDeleteCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;

interface MerchantRelationRequestEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function createMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestTransfer;

    /**
     * @param int $idMerchantRelationRequest
     * @param list<int> $companyBusinessUnitIds
     *
     * @return void
     */
    public function createAssigneeCompanyBusinessUnits(int $idMerchantRelationRequest, array $companyBusinessUnitIds): void;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function updateMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestDeleteCriteriaTransfer $merchantRelationRequestDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deleteMerchantRelationRequestCollection(
        MerchantRelationRequestDeleteCriteriaTransfer $merchantRelationRequestDeleteCriteriaTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer $merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deleteMerchantRelationRequestToCompanyBusinessUnitCollection(
        MerchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer $merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer
    ): void;
}
