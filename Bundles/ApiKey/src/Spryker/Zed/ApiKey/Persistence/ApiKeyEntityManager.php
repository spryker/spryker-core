<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Persistence;

use Generated\Shared\Transfer\ApiKeyTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ApiKey\Persistence\ApiKeyPersistenceFactory getFactory()
 */
class ApiKeyEntityManager extends AbstractEntityManager implements ApiKeyEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiKeyTransfer $apiKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyTransfer
     */
    public function createApiKey(ApiKeyTransfer $apiKeyTransfer): ApiKeyTransfer
    {
        $apiKeyEntity = $this->getFactory()
            ->createApiKeyEntity()
            ->fromArray($apiKeyTransfer->toArray());
        $apiKeyEntity->save();

        return $this->getFactory()
            ->createApiKeyMapper()
            ->mapApiKeyEntityToApiKeyTransfer($apiKeyEntity, $apiKeyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyTransfer $apiKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyTransfer
     */
    public function updateApiKey(ApiKeyTransfer $apiKeyTransfer): ApiKeyTransfer
    {
        $apiKeyEntity = $this->getFactory()
            ->createApiKeyQuery()
            ->filterByIdApiKey($apiKeyTransfer->getIdApiKeyOrFail())
            ->findOne();

        if ($apiKeyTransfer->getKeyHash() !== null) {
            $apiKeyEntity->setKeyHash($apiKeyTransfer->getKeyHashOrFail());
        }

        $apiKeyEntity->setName($apiKeyTransfer->getNameOrFail());
        $apiKeyEntity->setValidTo($apiKeyTransfer->getValidTo());
        $apiKeyEntity->save();

        return $this->getFactory()
            ->createApiKeyMapper()
            ->mapApiKeyEntityToApiKeyTransfer($apiKeyEntity, $apiKeyTransfer);
    }

    /**
     * @param array<int> $apiKeyIds
     *
     * @return void
     */
    public function deleteApiKeys(array $apiKeyIds): void
    {
        $this->getFactory()
            ->createApiKeyQuery()
            ->filterByIdApiKey_In($apiKeyIds)
            ->delete();
    }
}
