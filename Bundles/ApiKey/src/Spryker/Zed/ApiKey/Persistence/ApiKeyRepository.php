<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Persistence;

use Generated\Shared\Transfer\ApiKeyCollectionTransfer;
use Generated\Shared\Transfer\ApiKeyConditionsTransfer;
use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;
use Generated\Shared\Transfer\ApiKeyTransfer;
use Orm\Zed\ApiKey\Persistence\Base\SpyApiKeyQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ApiKey\Persistence\ApiKeyPersistenceFactory getFactory()
 */
class ApiKeyRepository extends AbstractRepository implements ApiKeyRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionTransfer
     */
    public function getApiKeyCollection(ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer): ApiKeyCollectionTransfer
    {
        $apiKeyQuery = $this->getFactory()->createApiKeyQuery();
        $apiKeysData = [];

        if ($apiKeyCriteriaTransfer->getApiKeyConditions() !== null) {
            $apiKeyQuery = $this->applyApiKeyConditions($apiKeyQuery, $apiKeyCriteriaTransfer->getApiKeyConditionsOrFail());
        }

        $apiKeysCollection = $apiKeyQuery->find();

        $apiKeyCollectionTransfer = new ApiKeyCollectionTransfer();

        if ($apiKeysCollection->getData() === []) {
            return $apiKeyCollectionTransfer;
        }

        return $this->getFactory()
            ->createApiKeyMapper()
            ->mapApiKeyEntityCollectionToApiKeyCollectionTransfer($apiKeysCollection, $apiKeyCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyTransfer $apiKeyTransfer
     *
     * @return bool
     */
    public function checkApiKeyNameExists(ApiKeyTransfer $apiKeyTransfer): bool
    {
        $apiKeyQuery = $this->getFactory()->createApiKeyQuery();

        if ($apiKeyTransfer->getIdApiKey() !== null) {
            $apiKeyQuery = $apiKeyQuery->filterByIdApiKey($apiKeyTransfer->getIdApiKeyOrFail(), Criteria::NOT_EQUAL);
        }

        $apiKeyData = $apiKeyQuery
            ->filterByName($apiKeyTransfer->getNameOrFail())
            ->findOne();

        if ($apiKeyData === null) {
            return false;
        }

        return true;
    }

    /**
     * @param \Orm\Zed\ApiKey\Persistence\Base\SpyApiKeyQuery $apiKeyQuery
     * @param \Generated\Shared\Transfer\ApiKeyConditionsTransfer $apiKeyConditionsTransfer
     *
     * @return \Orm\Zed\ApiKey\Persistence\Base\SpyApiKeyQuery
     */
    protected function applyApiKeyConditions(
        SpyApiKeyQuery $apiKeyQuery,
        ApiKeyConditionsTransfer $apiKeyConditionsTransfer
    ): SpyApiKeyQuery {
        if ($apiKeyConditionsTransfer->getApiKeyIds() !== []) {
            $apiKeyQuery = $apiKeyQuery->filterByIdApiKey_In(
                $apiKeyConditionsTransfer->getApiKeyIds(),
            );
        }

        return $apiKeyQuery;
    }
}
