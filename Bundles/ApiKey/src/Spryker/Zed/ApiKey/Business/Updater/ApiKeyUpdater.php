<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business\Updater;

use Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer;
use Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer;
use Generated\Shared\Transfer\ApiKeyCollectionTransfer;
use Generated\Shared\Transfer\ApiKeyConditionsTransfer;
use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\ApiKey\ApiKeyConfig;
use Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface;
use Spryker\Zed\ApiKey\Dependency\Service\ApiKeyToUtilTextServiceInterface;
use Spryker\Zed\ApiKey\Persistence\ApiKeyEntityManagerInterface;
use Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ApiKeyUpdater implements ApiKeyUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const ERROR_NOT_FOUND_ENTITY = 'Entity with ID `%d` was not found in the database.';

    /**
     * @var string
     */
    protected const ID_PLACEHOLDER = '%d';

    /**
     * @var \Spryker\Zed\ApiKey\Persistence\ApiKeyEntityManagerInterface
     */
    protected ApiKeyEntityManagerInterface $entityManager;

    /**
     * @var \Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface
     */
    protected ApiKeyRepositoryInterface $repository;

    /**
     * @var \Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface
     */
    protected ApiKeyValidatorInterface $validator;

    /**
     * @var \Spryker\Zed\ApiKey\Dependency\Service\ApiKeyToUtilTextServiceInterface
     */
    protected ApiKeyToUtilTextServiceInterface $utilTextService;

    /**
     * @var \Spryker\Zed\ApiKey\ApiKeyConfig
     */
    protected ApiKeyConfig $config;

    /**
     * @param \Spryker\Zed\ApiKey\Persistence\ApiKeyEntityManagerInterface $entityManager
     * @param \Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface $repository
     * @param \Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface $validator
     * @param \Spryker\Zed\ApiKey\Dependency\Service\ApiKeyToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\ApiKey\ApiKeyConfig $config
     */
    public function __construct(
        ApiKeyEntityManagerInterface $entityManager,
        ApiKeyRepositoryInterface $repository,
        ApiKeyValidatorInterface $validator,
        ApiKeyToUtilTextServiceInterface $utilTextService,
        ApiKeyConfig $config
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->validator = $validator;
        $this->utilTextService = $utilTextService;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    public function update(ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer): ApiKeyCollectionResponseTransfer
    {
        foreach ($apiKeyCollectionRequestTransfer->getApiKeys() as $apiKeyTransfer) {
            $apiKeyCollectionResponseTransfer = $this->validator->validate($apiKeyTransfer, new ApiKeyCollectionResponseTransfer());

            if ($apiKeyCollectionRequestTransfer->getIsTransactional() && $apiKeyCollectionResponseTransfer->getErrors()->count() !== 0) {
                return $apiKeyCollectionResponseTransfer;
            }

            if ($apiKeyTransfer->getKey() !== null) {
                $apiKeyTransfer->setKeyHash($this->utilTextService
                    ->hashValue(
                        $apiKeyTransfer->getKeyOrFail(),
                        $this->config->getHashAlgorithm(),
                    ));
            }
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($apiKeyCollectionRequestTransfer) {
            return $this->executeUpdateApiKeyCollectionTransaction($apiKeyCollectionRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    protected function executeUpdateApiKeyCollectionTransaction(
        ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
    ): ApiKeyCollectionResponseTransfer {
        $apiKeyCollectionResponseTransfer = new ApiKeyCollectionResponseTransfer();

        $apiKeyCollectionTransfer = $this->repository->getApiKeyCollection(
            $this->createApiKeyCriteriaTransfer($apiKeyCollectionRequestTransfer),
        );
        $indexedApiKeyTransfers = $this->indexApiKeyTransfer($apiKeyCollectionTransfer);

        foreach ($apiKeyCollectionRequestTransfer->getApiKeys() as $apiKeyTransfer) {
            if (!isset($indexedApiKeyTransfers[$apiKeyTransfer->getIdApiKeyOrFail()])) {
                $errorTransfer = (new ErrorTransfer())
                    ->setMessage(static::ERROR_NOT_FOUND_ENTITY)
                    ->setParameters([
                        static::ID_PLACEHOLDER => $apiKeyTransfer->getIdApiKeyOrFail(),
                    ]);

                return $apiKeyCollectionResponseTransfer->addError($errorTransfer);
            }

            $apiKeyTransfer = $this->entityManager->updateApiKey($apiKeyTransfer);

            $apiKeyCollectionResponseTransfer->addApiKey($apiKeyTransfer);
        }

        return $apiKeyCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyCollectionTransfer $apiKeyCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ApiKeyTransfer>
     */
    protected function indexApiKeyTransfer(ApiKeyCollectionTransfer $apiKeyCollectionTransfer): array
    {
        $indexedApiKeyTransfers = [];

        foreach ($apiKeyCollectionTransfer->getApiKeys() as $apiKeyTransfer) {
            $indexedApiKeyTransfers[$apiKeyTransfer->getIdApiKeyOrFail()] = $apiKeyTransfer;
        }

        return $indexedApiKeyTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCriteriaTransfer
     */
    protected function createApiKeyCriteriaTransfer(
        ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
    ): ApiKeyCriteriaTransfer {
        $apiKeyConditionsTransfer = new ApiKeyConditionsTransfer();

        foreach ($apiKeyCollectionRequestTransfer->getApiKeys() as $apiKeyTransfer) {
            $apiKeyConditionsTransfer->addIdApiKey($apiKeyTransfer->getIdApiKeyOrFail());
        }

        return (new ApiKeyCriteriaTransfer())->setApiKeyConditions($apiKeyConditionsTransfer);
    }
}
