<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business;

use Generated\Shared\Transfer\ApiKeyCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer;
use Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer;
use Generated\Shared\Transfer\ApiKeyCollectionTransfer;
use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;

interface ApiKeyFacadeInterface
{
    /**
     * Specification:
     * - Retrieves the collection of API authorization keys from persistence by criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionTransfer
     */
    public function getApiKeyCollection(ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer): ApiKeyCollectionTransfer;

    /**
     * Specification:
     * - Requires `ApiKeyCollectionRequestTransfer.ApiKeyTransfer.name`.
     * - Requires `ApiKeyCollectionRequestTransfer.isTransactional` to be set.
     * - Validates whether `ApiKeyCollectionRequestTransfer.ApiKeyTransfer.name` is in valid format or not.
     * - Generates the API authorization key.
     * - Stores collection of API authorization keys to the database.
     * - Uses `ApiKeyCollectionRequestTransfer.isTransactional` for transactional operation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    public function createApiKeyCollection(ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer): ApiKeyCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `ApiKeyCollectionRequestTransfer.ApiKeyTransfer.name`.
     * - Requires `ApiKeyCollectionRequestTransfer.isTransactional` to be set.
     * - Validates whether `ApiKeyCollectionRequestTransfer.ApiKeyTransfer.name` is in valid format or not.
     * - Updates `ApiKeyCollectionRequestTransfer.ApiKeyTransfer.name`.
     * - Regenerates and updates API authorization key.
     * - Uses `ApiKeyCollectionRequestTransfer.isTransactional` for transactional operation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    public function updateApiKeyCollection(ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer): ApiKeyCollectionResponseTransfer;

    /**
     * Specification:
     * - Requires `ApiKeyCollectionDeleteCriteriaTransfer.apiKeyIds`.
     * - Removes the collection of API authorization keys from persistence by `ApiKeyCollectionDeleteCriteriaTransfer.apiKeyIds`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiKeyCollectionDeleteCriteriaTransfer $apiKeyCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    public function deleteApiKeyCollection(ApiKeyCollectionDeleteCriteriaTransfer $apiKeyCollectionDeleteCriteriaTransfer): ApiKeyCollectionResponseTransfer;
}
