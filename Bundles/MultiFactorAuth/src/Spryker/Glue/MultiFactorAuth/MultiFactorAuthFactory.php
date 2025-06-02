<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface;
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
     * @return \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface
     */
    public function getCustomerClient(): MultiFactorAuthToCustomerClientInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    public function getCustomerMultiFactorAuthPlugins(): array
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::PLUGINS_CUSTOMER_MULTI_FACTOR_AUTH);
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
}
