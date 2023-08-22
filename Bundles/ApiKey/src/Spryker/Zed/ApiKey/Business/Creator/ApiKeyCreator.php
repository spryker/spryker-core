<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business\Creator;

use Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer;
use Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer;
use Spryker\Zed\ApiKey\ApiKeyConfig;
use Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface;
use Spryker\Zed\ApiKey\Dependency\Facade\ApiKeyToUserFacadeInterface;
use Spryker\Zed\ApiKey\Dependency\Service\ApiKeyToUtilTextServiceInterface;
use Spryker\Zed\ApiKey\Persistence\ApiKeyEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ApiKeyCreator implements ApiKeyCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ApiKey\Dependency\Facade\ApiKeyToUserFacadeInterface
     */
    protected ApiKeyToUserFacadeInterface $userFacade;

    /**
     * @var \Spryker\Zed\ApiKey\Persistence\ApiKeyEntityManagerInterface
     */
    protected ApiKeyEntityManagerInterface $entityManager;

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
     * @param \Spryker\Zed\ApiKey\Dependency\Facade\ApiKeyToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\ApiKey\Persistence\ApiKeyEntityManagerInterface $entityManager
     * @param \Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface $validator
     * @param \Spryker\Zed\ApiKey\Dependency\Service\ApiKeyToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\ApiKey\ApiKeyConfig $config
     */
    public function __construct(
        ApiKeyToUserFacadeInterface $userFacade,
        ApiKeyEntityManagerInterface $entityManager,
        ApiKeyValidatorInterface $validator,
        ApiKeyToUtilTextServiceInterface $utilTextService,
        ApiKeyConfig $config
    ) {
        $this->userFacade = $userFacade;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->utilTextService = $utilTextService;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    public function create(ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer): ApiKeyCollectionResponseTransfer
    {
        foreach ($apiKeyCollectionRequestTransfer->getApiKeys() as $apiKeyTransfer) {
            $apiKeyCollectionResponseTransfer = $this->validator->validate($apiKeyTransfer, new ApiKeyCollectionResponseTransfer());

            if ($apiKeyCollectionRequestTransfer->getIsTransactional() && $apiKeyCollectionResponseTransfer->getErrors()->count() !== 0) {
                return $apiKeyCollectionResponseTransfer;
            }

            $apiKeyTransfer->setCreatedBy($this->userFacade->getCurrentUser()->getIdUserOrFail())
                ->setKeyHash($this->utilTextService->hashValue(
                    $apiKeyTransfer->getKeyOrFail(),
                    $this->config->getHashAlgorithm(),
                ));
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($apiKeyCollectionRequestTransfer) {
            return $this->executeCreateApiKeyCollectionTransaction($apiKeyCollectionRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    protected function executeCreateApiKeyCollectionTransaction(
        ApiKeyCollectionRequestTransfer $apiKeyCollectionRequestTransfer
    ): ApiKeyCollectionResponseTransfer {
        $apiKeyCollectionResponseTransfer = new ApiKeyCollectionResponseTransfer();

        foreach ($apiKeyCollectionRequestTransfer->getApiKeys() as $apiKeyTransfer) {
            $apiKeyTransfer = $this->entityManager->createApiKey($apiKeyTransfer);

            $apiKeyCollectionResponseTransfer->addApiKey($apiKeyTransfer);
        }

        return $apiKeyCollectionResponseTransfer;
    }
}
