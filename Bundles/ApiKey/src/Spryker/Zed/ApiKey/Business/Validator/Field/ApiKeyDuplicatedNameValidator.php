<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business\Validator\Field;

use Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer;
use Generated\Shared\Transfer\ApiKeyTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface;
use Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface;

class ApiKeyDuplicatedNameValidator implements ApiKeyValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_DUPLICATED_NAME = 'The provided key name `%s` is duplicated. Use another one and try again.';

    /**
     * @var string
     */
    protected const KEY_NAME_PLACEHOLDER = '%s';

    /**
     * @var \Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface
     */
    protected ApiKeyRepositoryInterface $repository;

    /**
     * @param \Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface $repository
     */
    public function __construct(ApiKeyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyTransfer $apiKeyTransfer
     * @param \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer $apiKeyCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    public function validate(
        ApiKeyTransfer $apiKeyTransfer,
        ApiKeyCollectionResponseTransfer $apiKeyCollectionResponseTransfer
    ): ApiKeyCollectionResponseTransfer {
        if ($this->repository->checkApiKeyNameExists($apiKeyTransfer) === false) {
            return $apiKeyCollectionResponseTransfer;
        }

        $errorTransfer = (new ErrorTransfer())
            ->setMessage(static::ERROR_DUPLICATED_NAME)
            ->setParameters([
                static::KEY_NAME_PLACEHOLDER => $apiKeyTransfer->getNameOrFail(),
            ]);

        $apiKeyCollectionResponseTransfer->addError($errorTransfer);

        return $apiKeyCollectionResponseTransfer;
    }
}
