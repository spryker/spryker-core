<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business;

use Spryker\Zed\ApiKey\ApiKeyDependencyProvider;
use Spryker\Zed\ApiKey\Business\Creator\ApiKeyCreator;
use Spryker\Zed\ApiKey\Business\Creator\ApiKeyCreatorInterface;
use Spryker\Zed\ApiKey\Business\Deleter\ApiKeyDeleter;
use Spryker\Zed\ApiKey\Business\Deleter\ApiKeyDeleterInterface;
use Spryker\Zed\ApiKey\Business\Mapper\ApiKeyMapper;
use Spryker\Zed\ApiKey\Business\Updater\ApiKeyUpdater;
use Spryker\Zed\ApiKey\Business\Updater\ApiKeyUpdaterInterface;
use Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidator;
use Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface;
use Spryker\Zed\ApiKey\Business\Validator\Field\ApiKeyDuplicatedNameValidator;
use Spryker\Zed\ApiKey\Business\Validator\Field\ApiKeyNameValidator;
use Spryker\Zed\ApiKey\Dependency\Facade\ApiKeyToUserFacadeInterface;
use Spryker\Zed\ApiKey\Dependency\Service\ApiKeyToUtilTextServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ApiKey\Persistence\ApiKeyRepositoryInterface getRepository()
 * @method \Spryker\Zed\ApiKey\Persistence\ApiKeyEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ApiKey\ApiKeyConfig getConfig()
 */
class ApiKeyBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ApiKey\Business\Creator\ApiKeyCreatorInterface
     */
    public function createApiKeyCreator(): ApiKeyCreatorInterface
    {
        return new ApiKeyCreator(
            $this->getUserFacade(),
            $this->getEntityManager(),
            $this->createApiKeyValidator(),
            $this->getUtilTextService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\ApiKey\Business\Updater\ApiKeyUpdaterInterface
     */
    public function createApiKeyUpdater(): ApiKeyUpdaterInterface
    {
        return new ApiKeyUpdater(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createApiKeyValidator(),
            $this->getUtilTextService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\ApiKey\Business\Deleter\ApiKeyDeleterInterface
     */
    public function createApiKeyDeleter(): ApiKeyDeleterInterface
    {
        return new ApiKeyDeleter(
            $this->getEntityManager(),
            $this->createApiKeyMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface
     */
    public function createApiKeyValidator(): ApiKeyValidatorInterface
    {
        return new ApiKeyValidator(
            $this->getApiKeyValidators(),
        );
    }

    /**
     * @return array<\Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface>
     */
    public function getApiKeyValidators(): array
    {
        return [
            $this->createApiKeyNameValidator(),
            $this->createApiKeyDuplicatedNameValidator(),
        ];
    }

    /**
     * @return \Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface
     */
    public function createApiKeyNameValidator(): ApiKeyValidatorInterface
    {
        return new ApiKeyNameValidator();
    }

    /**
     * @return \Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface
     */
    public function createApiKeyDuplicatedNameValidator(): ApiKeyValidatorInterface
    {
        return new ApiKeyDuplicatedNameValidator(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\ApiKey\Business\Mapper\ApiKeyMapper
     */
    public function createApiKeyMapper(): ApiKeyMapper
    {
        return new ApiKeyMapper();
    }

    /**
     * @return \Spryker\Zed\ApiKey\Dependency\Service\ApiKeyToUtilTextServiceInterface
     */
    public function getUtilTextService(): ApiKeyToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(ApiKeyDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\ApiKey\Dependency\Facade\ApiKeyToUserFacadeInterface
     */
    public function getUserFacade(): ApiKeyToUserFacadeInterface
    {
        return $this->getProvidedDependency(ApiKeyDependencyProvider::FACADE_USER);
    }
}
