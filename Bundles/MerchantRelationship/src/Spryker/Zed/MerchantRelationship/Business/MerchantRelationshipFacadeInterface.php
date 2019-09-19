<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableDataTransfer;

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

    /**
     * Specification:
     * - Removes related business units by assigneeCompanyBusinessUnitCollection.
     * - Executes pre-delete plugin stack MerchantRelationshipPreDeletePluginInterface.
     * - Finds a merchant relationship record by ID in DB.
     * - Removes the merchant relationship record.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function deleteMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): void;

    /**
     * Specification:
     * - Returns a merchant relationship by merchant relationship id in provided transfer.
     * - Throws an exception in case a record is not found.
     * - Populates name in transfer.
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
     * - Returns a merchant relationship by merchant relationship key in provided transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipByKey(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?MerchantRelationshipTransfer;

    /**
     * Specification:
     * - Returns all merchant relations.
     * - Hydrate owner company business unit and merchant.
     * - Populates name in transfer.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getMerchantRelationshipCollection(): array;

    /**
     * Specification:
     * - Finds a merchant relationship by merchant relationship id in provided transfer.
     * - Returns MerchantRelationshipTransfer if found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?MerchantRelationshipTransfer;

    /**
     * Specification:
     * - Finds merchant relationships which use given product list by ProductListTransfer::idProductList.
     * - Returns ProductListResponseTransfer with check results.
     * - ProductListResponseTransfer::isSuccessful is equal to true when usage cases were not found, false otherwise.
     * - ProductListResponseTransfer::messages contains usage details.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function checkProductListUsageAmongMerchantRelationships(ProductListTransfer $productListTransfer): ProductListResponseTransfer;

    /**
     * Specification:
     * - Finds merchant relationships which use given product list.
     * - Uses idProductList property from ProductListUsedByTableDataTransfer::ProductList to filter records.
     * - Expands UsedByTableDataTransfer::rows with found data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    public function expandProductListUsedByTableData(ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer): ProductListUsedByTableDataTransfer;
}
