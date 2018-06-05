<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipEntityManagerInterface
{
    /**
     * Specification:
     * - Finds a merchant relationship by merchant relationship ID.
     * - Deletes the merchant relationship.
     *
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function deleteMerchantRelationshipById(int $idMerchantRelationship): void;

    /**
     * Specification:
     * - Creates a merchant relationship.
     * - Finds a merchant relationship by MerchantRelationshipTransfer::idMerchantRelationship in the transfer.
     * - Updates fields in a merchant relationship entity.
     * - Persists the entity to DB.
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function saveMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer;

    /**
     * Specification:
     * - Creates a merchant relationship to company business unit relation entities.
     *
     * @param int[] $idAssignedCompanyBusinessUnits
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function addAssignedCompanyBusinessUnits(array $idAssignedCompanyBusinessUnits, int $idMerchantRelationship);

    /**
     * Specification:
     * - Deletes a merchant relationship to company business unit relation entities.
     *
     * @param int[] $idAssignedCompanyBusinessUnits
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function removeAssignedCompanyBusinessUnits(array $idAssignedCompanyBusinessUnits, int $idMerchantRelationship);
}
