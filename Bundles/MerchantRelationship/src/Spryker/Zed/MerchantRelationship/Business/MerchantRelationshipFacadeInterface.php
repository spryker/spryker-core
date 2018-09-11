<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business;

use Generated\Shared\Transfer\MerchantRelationshipDeleteResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

interface MerchantRelationshipFacadeInterface
{
    /**
     * Specification:
     * - Creates a new merchant relationship entity.
     * - Uses incoming transfer to set entity fields.
     * - Persists the entity to DB.
     * - Sets ID to the returning transfer.
     * - Creates new assignee relations by AssigneeCompanyBusinessUnitCollection (fk_merchant_relation, fk_company_business_unit).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer;

    /**
     * Specification:
     * - Finds a merchant record by ID in DB.
     * - Uses incoming transfer to update entity fields.
     * - Persists the entity to DB.
     * - Removes outdated relations by assigneeCompanyBusinessUnitCollection (fk_merchant_relation, fk_company_business_unit).
     * - Creates new relations by AssigneeCompanyBusinessUnitCollection (fk_merchant_relation, fk_company_business_unit).
     * - Throws MerchantRelationNotFoundException in case a record is not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function updateMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer;

    /***
     * Specification:
     * - Removes related business units by assigneeCompanyBusinessUnitCollection.
     * - Finds a merchant relationship record by ID in DB.
     * - Removes the merchant relationship record.
     *
     * @api
     *
     * @deprecated Use MerchantRelationshipFacadeInterface::deleteMerchantRelationshipWithPreCheck() instead
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function deleteMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): void;

    /**
     * Specification:
     * - Runs all MerchantRelationshipPreDeletePlugins to check if the relationship can be safely deleted.
     * - Removes related business units by assigneeCompanyBusinessUnitCollection.
     * - Finds a merchant relationship record by ID in DB.
     * - Removes the merchant relationship record.
     * - Returns MerchantRelationshipDeleteResponseTransfer which contains the operation result as well as the error messages of any.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipDeleteResponseTransfer
     */
    public function deleteMerchantRelationshipWithPreCheck(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipDeleteResponseTransfer;

    /**
     * Specification:
     * - Returns a merchant relationship by merchant relationship id in provided transfer.
     * - Throws an exception in case a record is not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer;

    /**
     * Specification:
     * - Returns all merchant relations.
     * - Hydrate owner company business unit and merchant
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getMerchantRelationshipCollection(): array;
}
