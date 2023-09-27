<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business\Reader;

use Generated\Shared\Transfer\ApiKeyCollectionTransfer;
use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;
use Spryker\Zed\ApiKey\Business\Hasher\ApiKeyHasherInterface;
use Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface;

class ApiKeyReader implements ApiKeyReaderInterface
{
    /**
     * @var \Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface
     */
    protected ApiKeyRepositoryInterface $repository;

    /**
     * @var \Spryker\Zed\ApiKey\Business\Hasher\ApiKeyHasherInterface
     */
    protected ApiKeyHasherInterface $apiKeyHasher;

    /**
     * @param \Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface $repository
     * @param \Spryker\Zed\ApiKey\Business\Hasher\ApiKeyHasherInterface $apiKeyHasher
     */
    public function __construct(
        ApiKeyRepositoryInterface $repository,
        ApiKeyHasherInterface $apiKeyHasher
    ) {
        $this->repository = $repository;
        $this->apiKeyHasher = $apiKeyHasher;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionTransfer
     */
    public function getApiKeyCollection(ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer): ApiKeyCollectionTransfer
    {
        $apiKeysHashes = $this->apiKeyHasher->getApiKeyHashes($apiKeyCriteriaTransfer);

        return $this->repository->getApiKeyCollection($apiKeyCriteriaTransfer, $apiKeysHashes);
    }
}
