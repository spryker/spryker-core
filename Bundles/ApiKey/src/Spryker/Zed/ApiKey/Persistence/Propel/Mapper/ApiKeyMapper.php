<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ApiKeyCollectionTransfer;
use Generated\Shared\Transfer\ApiKeyTransfer;
use Orm\Zed\ApiKey\Persistence\Base\SpyApiKey;
use Propel\Runtime\Collection\Collection;

class ApiKeyMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\ApiKey\Persistence\Base\SpyApiKey> $apiKeysCollection
     * @param \Generated\Shared\Transfer\ApiKeyCollectionTransfer $apiKeyCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionTransfer
     */
    public function mapApiKeyEntityCollectionToApiKeyCollectionTransfer(
        Collection $apiKeysCollection,
        ApiKeyCollectionTransfer $apiKeyCollectionTransfer
    ): ApiKeyCollectionTransfer {
        foreach ($apiKeysCollection as $apiKeyEntity) {
            $apiKeyCollectionTransfer->addApiKey(
                $this->mapApiKeyEntityToApiKeyTransfer($apiKeyEntity, new ApiKeyTransfer()),
            );
        }

        return $apiKeyCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ApiKey\Persistence\Base\SpyApiKey $apiKeyEntity
     * @param \Generated\Shared\Transfer\ApiKeyTransfer $apiKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyTransfer
     */
    public function mapApiKeyEntityToApiKeyTransfer(
        SpyApiKey $apiKeyEntity,
        ApiKeyTransfer $apiKeyTransfer
    ): ApiKeyTransfer {
        return (new ApiKeyTransfer())
            ->fromArray($apiKeyEntity->toArray(), true)
            ->setKey($apiKeyTransfer->getKey())
            ->setKeyHash(null);
    }
}
