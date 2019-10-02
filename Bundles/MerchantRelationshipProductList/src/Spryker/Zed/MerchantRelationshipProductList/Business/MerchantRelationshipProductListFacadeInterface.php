<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\ProductListCollectionTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;

interface MerchantRelationshipProductListFacadeInterface
{
    /**
     * Specification:
     * - Finds product lists by company business unit.
     * - Expands customer transfer with CustomerProductListCollectionTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandCustomerTransferWithProductListIds(CustomerTransfer $customerTransfer): CustomerTransfer;

    /**
     * Specification:
     * - Finds product lists by merchant relationship.
     * - MerchantRelationshipTransfer has to contain MerchantRelationship ID as the required field.
     * - Removes merchant relationship from product list.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function deleteProductListsByMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): void;

    /**
     * Specification:
     * - Returns unassigned product lists or assigned to provided Merchant Relationship.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListCollectionTransfer
     */
    public function getAvailableProductListsForMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): ProductListCollectionTransfer;

    /**
     * Specification:
     * - Adjusts "merchant relationshop - product list" assignments in Persistence according provided product list collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function updateProductListMerchantRelationshipAssignments(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer;

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
     * - Uses ProductListTransfer::idProductList for filtering.
     * - Returns array of merchant relationship IDs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return int[]
     */
    public function getMerchantRelationshipIdsByProductList(ProductListTransfer $productListTransfer): array;
}
