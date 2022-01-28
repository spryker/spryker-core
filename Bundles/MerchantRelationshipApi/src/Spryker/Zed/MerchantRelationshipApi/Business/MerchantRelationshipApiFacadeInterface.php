<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

interface MerchantRelationshipApiFacadeInterface
{
    /**
     * Specification:
     * - Deletes a merchant relationship.
     *
     * @api
     *
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function deleteMerchantRelationship(int $idMerchantRelationship): ApiItemTransfer;

    /**
     * Specification:
     * - Creates a merchant relationship.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createMerchantRelationship(ApiDataTransfer $apiDataTransfer): ApiItemTransfer;

    /**
     * Specification:
     * - Updates a merchant relationship.
     *
     * @api
     *
     * @param int $id
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function updateMerchantRelationship(int $id, ApiDataTransfer $apiDataTransfer): ApiItemTransfer;

    /**
     * Specification:
     * - Retrieves a collection that can be filtered, sorted and paginated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function getMerchantRelationshipCollection(ApiRequestTransfer $apiRequestTransfer): ApiCollectionTransfer;

    /**
     * Specification:
     *  - Finds merchant relationship by merchant relationship ID.
     *  - Returns `ApiItemTransfer` with error message if not found.
     *
     * @api
     *
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function getMerchantRelationship(int $idMerchantRelationship): ApiItemTransfer;

    /**
     * Specification:
     * - Validates if all required fields are present in `ApiRequestTransfer.requestData`.
     * - Returns an array of errors if any occurs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    public function validateMerchantRelationshipRequestData(ApiRequestTransfer $apiRequestTransfer): array;
}
