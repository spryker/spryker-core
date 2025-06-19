<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\MultiFactorAuth\BackendApi\Activate\MultiFactorAuthTypeActivateProcessor as MultiFactorAuthTypeBackendApiActivateProcessor;
use Spryker\Glue\MultiFactorAuth\BackendApi\Activate\MultiFactorAuthTypeActivateProcessorInterface as MultiFactorAuthTypeBackendApiActivateProcessorInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\Deactivate\MultiFactorAuthTypeDeactivateProcessor as MultiFactorAuthTypeBackendApiDeactivateProcessor;
use Spryker\Glue\MultiFactorAuth\BackendApi\Deactivate\MultiFactorAuthTypeDeactivateProcessorInterface as MultiFactorAuthTypeBackendApiDeactivateProcessorInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\Reader\MultiFactorAuthTypesReader as MultiFactorAuthTypesBackendApiReader;
use Spryker\Glue\MultiFactorAuth\BackendApi\Reader\MultiFactorAuthTypesReaderInterface as MultiFactorAuthTypesBackendApiReaderInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilder as MultiFactorAuthBackendApiResponseBuilder;
use Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface as MultiFactorAuthBackendApiResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilder as MultiFactorAuthBackendApiTransferBuilder;
use Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface as MultiFactorAuthBackendApiTransferBuilderInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\Trigger\MultiFactorAuthTriggerProcessor as MultiFactorAuthBackendApiTriggerProcessor;
use Spryker\Glue\MultiFactorAuth\BackendApi\Trigger\MultiFactorAuthTriggerProcessorInterface as MultiFactorAuthBackendApiTriggerProcessorInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\Validator\MultiFactorAuthBackendApiRequestValidator;
use Spryker\Glue\MultiFactorAuth\BackendApi\Validator\MultiFactorAuthBackendApiRequestValidatorInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\Validator\MultiFactorAuthValidator as MultiFactorAuthBackendApiValidator;
use Spryker\Glue\MultiFactorAuth\BackendApi\Validator\MultiFactorAuthValidatorInterface as MultiFactorAuthBackendApiValidatorInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\Verify\MultiFactorAuthTypeVerifyProcessor as MultiFactorAuthTypeBackendApiVerifyProcessor;
use Spryker\Glue\MultiFactorAuth\BackendApi\Verify\MultiFactorAuthTypeVerifyProcessorInterface as MultiFactorAuthTypeBackendApiVerifyProcessorInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface;
use Spryker\Glue\MultiFactorAuth\Processor\Activate\MultiFactorAuthActivateProcessor;
use Spryker\Glue\MultiFactorAuth\Processor\Activate\MultiFactorAuthActivateProcessorInterface;
use Spryker\Glue\MultiFactorAuth\Processor\Deactivate\MultiFactorAuthTypeDeactivateProcessor;
use Spryker\Glue\MultiFactorAuth\Processor\Deactivate\MultiFactorAuthTypeDeactivateProcessorInterface;
use Spryker\Glue\MultiFactorAuth\Processor\Reader\MultiFactorAuthTypesReader;
use Spryker\Glue\MultiFactorAuth\Processor\Reader\MultiFactorAuthTypesReaderInterface;
use Spryker\Glue\MultiFactorAuth\Processor\ResponseBuilder\MultiFactorAuthResponseBuilder;
use Spryker\Glue\MultiFactorAuth\Processor\ResponseBuilder\MultiFactorAuthResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\Processor\TransferBuilder\MultiFactorAuthTransferBuilder;
use Spryker\Glue\MultiFactorAuth\Processor\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Glue\MultiFactorAuth\Processor\Trigger\MultiFactorAuthTriggerProcessor;
use Spryker\Glue\MultiFactorAuth\Processor\Trigger\MultiFactorAuthTriggerProcessorInterface;
use Spryker\Glue\MultiFactorAuth\Processor\Validator\MultiFactorAuthRestUserValidator;
use Spryker\Glue\MultiFactorAuth\Processor\Validator\MultiFactorAuthRestUserValidatorInterface;
use Spryker\Glue\MultiFactorAuth\Processor\Validator\MultiFactorAuthValidator;
use Spryker\Glue\MultiFactorAuth\Processor\Validator\MultiFactorAuthValidatorInterface;
use Spryker\Glue\MultiFactorAuth\Processor\Verify\MultiFactorAuthTypeVerifyProcessor;
use Spryker\Glue\MultiFactorAuth\Processor\Verify\MultiFactorAuthTypeVerifyProcessorInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Activate\MultiFactorAuthTypeActivateProcessor as MultiFactorAuthTypeStorefrontApiActivateProcessor;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Activate\MultiFactorAuthTypeActivateProcessorInterface as MultiFactorAuthTypeStorefrontApiActivateProcessorInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Deactivate\MultiFactorAuthTypeDeactivateProcessor as MultiFactorAuthTypeStorefrontApiDeactivateProcessor;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Deactivate\MultiFactorAuthTypeDeactivateProcessorInterface as MultiFactorAuthTypeStorefrontApiDeactivateProcessorInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Reader\MultiFactorAuthTypesReader as MultiFactorAuthTypesStorefrontApiReader;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Reader\MultiFactorAuthTypesReaderInterface as MultiFactorAuthTypesStorefrontApiReaderInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\ResponseBuilder\MultiFactorAuthResponseBuilder as MultiFactorAuthStorefrontApiResponseBuilder;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface as MultiFactorAuthStorefrontApiResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\TransferBuilder\MultiFactorAuthTransferBuilder as MultiFactorAuthStorefrontApiTransferBuilder;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface as MultiFactorAuthStorefrontApiTransferBuilderInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Trigger\MultiFactorAuthTriggerProcessor as MultiFactorAuthStorefrontApiTriggerProcessor;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Trigger\MultiFactorAuthTriggerProcessorInterface as MultiFactorAuthStorefrontApiTriggerProcessorInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Validator\MultiFactorAuthStorefrontApiRequestValidator;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Validator\MultiFactorAuthStorefrontApiRequestValidatorInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Validator\MultiFactorAuthValidator as MultiFactorAuthStorefrontApiValidator;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Validator\MultiFactorAuthValidatorInterface as MultiFactorAuthStorefrontApiValidatorInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Verify\MultiFactorAuthTypeVerifyProcessor as MultiFactorAuthTypeStorefrontApiVerifyProcessor;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\Verify\MultiFactorAuthTypeVerifyProcessorInterface as MultiFactorAuthTypeStorefrontApiVerifyProcessorInterface;

/**
 * @method \Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class MultiFactorAuthFactory extends AbstractBackendApiFactory
{
    /**
     * @return \Spryker\Glue\MultiFactorAuth\Processor\Reader\MultiFactorAuthTypesReaderInterface
     */
    public function createMultiFactorAuthTypesReader(): MultiFactorAuthTypesReaderInterface
    {
        return new MultiFactorAuthTypesReader(
            $this->getMultiFactorAuthClient(),
            $this->getResourceBuilder(),
            $this->getCustomerMultiFactorAuthPlugins(),
            $this->createMultiFactorAuthResponseBuilder(),
            $this->createMultiFactorAuthTransferBuilder(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Processor\Trigger\MultiFactorAuthTriggerProcessorInterface
     */
    public function createMultiFactorAuthTriggerProcessor(): MultiFactorAuthTriggerProcessorInterface
    {
        return new MultiFactorAuthTriggerProcessor(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerClient(),
            $this->createMultiFactorAuthResponseBuilder(),
            $this->createMultiFactorAuthTransferBuilder(),
            $this->createMultiFactorAuthValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Processor\Activate\MultiFactorAuthActivateProcessorInterface
     */
    public function createMultiFactorAuthActivateProcessor(): MultiFactorAuthActivateProcessorInterface
    {
        return new MultiFactorAuthActivateProcessor(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerClient(),
            $this->createMultiFactorAuthResponseBuilder(),
            $this->createMultiFactorAuthTransferBuilder(),
            $this->createMultiFactorAuthValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Processor\Verify\MultiFactorAuthTypeVerifyProcessorInterface
     */
    public function createMultiFactorAuthTypeVerifyProcessor(): MultiFactorAuthTypeVerifyProcessorInterface
    {
        return new MultiFactorAuthTypeVerifyProcessor(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerClient(),
            $this->createMultiFactorAuthResponseBuilder(),
            $this->createMultiFactorAuthTransferBuilder(),
            $this->createMultiFactorAuthValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Processor\Deactivate\MultiFactorAuthTypeDeactivateProcessorInterface
     */
    public function createMultiFactorAuthTypeDeactivateProcessor(): MultiFactorAuthTypeDeactivateProcessorInterface
    {
        return new MultiFactorAuthTypeDeactivateProcessor(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerClient(),
            $this->createMultiFactorAuthResponseBuilder(),
            $this->createMultiFactorAuthTransferBuilder(),
            $this->createMultiFactorAuthValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Processor\Validator\MultiFactorAuthRestUserValidatorInterface
     */
    public function createMultiFactorAuthRestUserValidator(): MultiFactorAuthRestUserValidatorInterface
    {
        return new MultiFactorAuthRestUserValidator(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerClient(),
            $this->getConfig(),
            $this->getResourceBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface
     */
    public function getMultiFactorAuthClient(): MultiFactorAuthToMultiFactorAuthClientInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::CLIENT_MULTI_FACTOR_AUTH);
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface
     */
    public function getMultiFactorAuthFacade(): MultiFactorAuthToMultiFactorAuthFacadeInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::FACADE_MULTI_FACTOR_AUTH);
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface
     */
    public function getCustomerClient(): MultiFactorAuthToCustomerClientInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface
     */
    public function getUserFacade(): MultiFactorAuthToUserFacadeInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::FACADE_USER);
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    public function getCustomerMultiFactorAuthPlugins(): array
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::PLUGINS_CUSTOMER_MULTI_FACTOR_AUTH);
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    public function getUserMultiFactorAuthPlugins(): array
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::PLUGINS_USER_MULTI_FACTOR_AUTH);
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Processor\ResponseBuilder\MultiFactorAuthResponseBuilderInterface
     */
    public function createMultiFactorAuthResponseBuilder(): MultiFactorAuthResponseBuilderInterface
    {
        return new MultiFactorAuthResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Processor\Validator\MultiFactorAuthValidatorInterface
     */
    public function createMultiFactorAuthValidator(): MultiFactorAuthValidatorInterface
    {
        return new MultiFactorAuthValidator(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerMultiFactorAuthPlugins(),
            $this->createMultiFactorAuthResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\Processor\TransferBuilder\MultiFactorAuthTransferBuilderInterface
     */
    public function createMultiFactorAuthTransferBuilder(): MultiFactorAuthTransferBuilderInterface
    {
        return new MultiFactorAuthTransferBuilder();
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\BackendApi\Activate\MultiFactorAuthTypeActivateProcessorInterface
     */
    public function createMultiFactorAuthBackendApiActivateProcessor(): MultiFactorAuthTypeBackendApiActivateProcessorInterface
    {
        return new MultiFactorAuthTypeBackendApiActivateProcessor(
            $this->getMultiFactorAuthFacade(),
            $this->getUserFacade(),
            $this->createMultiFactorAuthBackendApiResponseBuilder(),
            $this->createMultiFactorAuthBackendApiTransferBuilder(),
            $this->createMultiFactorAuthBackendApiValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\BackendApi\Verify\MultiFactorAuthTypeVerifyProcessorInterface
     */
    public function createMultiFactorAuthBackendApiVerifyProcessor(): MultiFactorAuthTypeBackendApiVerifyProcessorInterface
    {
        return new MultiFactorAuthTypeBackendApiVerifyProcessor(
            $this->getMultiFactorAuthFacade(),
            $this->getUserFacade(),
            $this->createMultiFactorAuthBackendApiResponseBuilder(),
            $this->createMultiFactorAuthBackendApiTransferBuilder(),
            $this->createMultiFactorAuthBackendApiValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\BackendApi\Deactivate\MultiFactorAuthTypeDeactivateProcessorInterface
     */
    public function createMultiFactorAuthBackendApiDeactivateProcessor(): MultiFactorAuthTypeBackendApiDeactivateProcessorInterface
    {
        return new MultiFactorAuthTypeBackendApiDeactivateProcessor(
            $this->getMultiFactorAuthFacade(),
            $this->getUserFacade(),
            $this->createMultiFactorAuthBackendApiResponseBuilder(),
            $this->createMultiFactorAuthBackendApiTransferBuilder(),
            $this->createMultiFactorAuthBackendApiValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\BackendApi\Trigger\MultiFactorAuthTriggerProcessorInterface
     */
    public function createMultiFactorAuthBackendApiTriggerProcessor(): MultiFactorAuthBackendApiTriggerProcessorInterface
    {
        return new MultiFactorAuthBackendApiTriggerProcessor(
            $this->getMultiFactorAuthFacade(),
            $this->getUserFacade(),
            $this->createMultiFactorAuthBackendApiResponseBuilder(),
            $this->createMultiFactorAuthBackendApiTransferBuilder(),
            $this->createMultiFactorAuthBackendApiValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface
     */
    public function createMultiFactorAuthBackendApiResponseBuilder(): MultiFactorAuthBackendApiResponseBuilderInterface
    {
        return new MultiFactorAuthBackendApiResponseBuilder();
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface
     */
    public function createMultiFactorAuthBackendApiTransferBuilder(): MultiFactorAuthBackendApiTransferBuilderInterface
    {
        return new MultiFactorAuthBackendApiTransferBuilder();
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\BackendApi\Validator\MultiFactorAuthValidatorInterface
     */
    public function createMultiFactorAuthBackendApiValidator(): MultiFactorAuthBackendApiValidatorInterface
    {
        return new MultiFactorAuthBackendApiValidator(
            $this->getMultiFactorAuthFacade(),
            $this->getUserMultiFactorAuthPlugins(),
            $this->createMultiFactorAuthBackendApiResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\BackendApi\Reader\MultiFactorAuthTypesReaderInterface
     */
    public function createMultiFactorAuthBackendApiReader(): MultiFactorAuthTypesBackendApiReaderInterface
    {
        return new MultiFactorAuthTypesBackendApiReader(
            $this->getMultiFactorAuthFacade(),
            $this->getUserMultiFactorAuthPlugins(),
            $this->createMultiFactorAuthBackendApiResponseBuilder(),
            $this->createMultiFactorAuthBackendApiTransferBuilder(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\BackendApi\Validator\MultiFactorAuthBackendApiRequestValidatorInterface
     */
    public function createMultiFactorAuthBackendApiRequestValidator(): MultiFactorAuthBackendApiRequestValidatorInterface
    {
        return new MultiFactorAuthBackendApiRequestValidator(
            $this->getMultiFactorAuthFacade(),
            $this->getUserFacade(),
            $this->createMultiFactorAuthBackendApiTransferBuilder(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\StorefrontApi\Reader\MultiFactorAuthTypesReaderInterface
     */
    public function createMultiFactorAuthStorefrontApiReader(): MultiFactorAuthTypesStorefrontApiReaderInterface
    {
        return new MultiFactorAuthTypesStorefrontApiReader(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerMultiFactorAuthPlugins(),
            $this->createMultiFactorAuthStorefrontApiResponseBuilder(),
            $this->createMultiFactorAuthStorefrontApiTransferBuilder(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\StorefrontApi\Activate\MultiFactorAuthTypeActivateProcessorInterface
     */
    public function createMultiFactorAuthTypeStorefrontApiActivateProcessor(): MultiFactorAuthTypeStorefrontApiActivateProcessorInterface
    {
        return new MultiFactorAuthTypeStorefrontApiActivateProcessor(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerClient(),
            $this->createMultiFactorAuthStorefrontApiResponseBuilder(),
            $this->createMultiFactorAuthStorefrontApiTransferBuilder(),
            $this->createMultiFactorAuthStorefrontApiValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\StorefrontApi\Verify\MultiFactorAuthTypeVerifyProcessorInterface
     */
    public function createMultiFactorAuthTypeStorefrontApiVerifyProcessor(): MultiFactorAuthTypeStorefrontApiVerifyProcessorInterface
    {
        return new MultiFactorAuthTypeStorefrontApiVerifyProcessor(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerClient(),
            $this->createMultiFactorAuthStorefrontApiResponseBuilder(),
            $this->createMultiFactorAuthStorefrontApiTransferBuilder(),
            $this->createMultiFactorAuthStorefrontApiValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\StorefrontApi\Deactivate\MultiFactorAuthTypeDeactivateProcessorInterface
     */
    public function createMultiFactorAuthTypeStorefrontApiDeactivateProcessor(): MultiFactorAuthTypeStorefrontApiDeactivateProcessorInterface
    {
        return new MultiFactorAuthTypeStorefrontApiDeactivateProcessor(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerClient(),
            $this->createMultiFactorAuthStorefrontApiResponseBuilder(),
            $this->createMultiFactorAuthStorefrontApiTransferBuilder(),
            $this->createMultiFactorAuthStorefrontApiValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\StorefrontApi\Trigger\MultiFactorAuthTriggerProcessorInterface
     */
    public function createMultiFactorAuthStorefrontApiTriggerProcessor(): MultiFactorAuthStorefrontApiTriggerProcessorInterface
    {
        return new MultiFactorAuthStorefrontApiTriggerProcessor(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerClient(),
            $this->createMultiFactorAuthStorefrontApiResponseBuilder(),
            $this->createMultiFactorAuthStorefrontApiTransferBuilder(),
            $this->createMultiFactorAuthStorefrontApiValidator(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\StorefrontApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface
     */
    public function createMultiFactorAuthStorefrontApiResponseBuilder(): MultiFactorAuthStorefrontApiResponseBuilderInterface
    {
        return new MultiFactorAuthStorefrontApiResponseBuilder();
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\StorefrontApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface
     */
    public function createMultiFactorAuthStorefrontApiTransferBuilder(): MultiFactorAuthStorefrontApiTransferBuilderInterface
    {
        return new MultiFactorAuthStorefrontApiTransferBuilder();
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\StorefrontApi\Validator\MultiFactorAuthValidatorInterface
     */
    public function createMultiFactorAuthStorefrontApiValidator(): MultiFactorAuthStorefrontApiValidatorInterface
    {
        return new MultiFactorAuthStorefrontApiValidator(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerMultiFactorAuthPlugins(),
            $this->createMultiFactorAuthStorefrontApiResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\MultiFactorAuth\StorefrontApi\Validator\MultiFactorAuthStorefrontApiRequestValidatorInterface
     */
    public function createMultiFactorAuthStorefrontApiRequestValidator(): MultiFactorAuthStorefrontApiRequestValidatorInterface
    {
        return new MultiFactorAuthStorefrontApiRequestValidator(
            $this->getMultiFactorAuthClient(),
            $this->getCustomerClient(),
            $this->createMultiFactorAuthStorefrontApiTransferBuilder(),
            $this->getConfig(),
        );
    }
}
