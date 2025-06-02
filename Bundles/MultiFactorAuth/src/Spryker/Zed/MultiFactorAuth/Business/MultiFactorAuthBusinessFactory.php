<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGenerator;
use Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGeneratorInterface;
use Spryker\Zed\MultiFactorAuth\Business\Generator\Provider\CodeGeneratorConfigProviderInterface;
use Spryker\Zed\MultiFactorAuth\Business\Generator\Provider\Customer\CustomerCodeGeneratorConfigProvider;
use Spryker\Zed\MultiFactorAuth\Business\Generator\Provider\User\UserCodeGeneratorConfigProvider;
use Spryker\Zed\MultiFactorAuth\Business\Reader\User\UserMultiFactorAuthReader;
use Spryker\Zed\MultiFactorAuth\Business\Reader\User\UserMultiFactorAuthReaderInterface;
use Spryker\Zed\MultiFactorAuth\Business\Sender\CodeSenderInterface;
use Spryker\Zed\MultiFactorAuth\Business\Sender\Customer\CustomerCodeSender;
use Spryker\Zed\MultiFactorAuth\Business\Sender\User\UserCodeSender;
use Spryker\Zed\MultiFactorAuth\Business\Validator\CodeValidatorInterface;
use Spryker\Zed\MultiFactorAuth\Business\Validator\Customer\CustomerCodeValidator;
use Spryker\Zed\MultiFactorAuth\Business\Validator\Customer\CustomerMultiFactorAuthStatusValidator;
use Spryker\Zed\MultiFactorAuth\Business\Validator\MultiFactorAuthStatusValidatorInterface;
use Spryker\Zed\MultiFactorAuth\Business\Validator\User\UserCodeValidator;
use Spryker\Zed\MultiFactorAuth\Business\Validator\User\UserMultiFactorAuthStatusValidator;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToGlossaryFacadeInterface;
use Spryker\Zed\MultiFactorAuth\MultiFactorAuthDependencyProvider;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class MultiFactorAuthBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Validator\MultiFactorAuthStatusValidatorInterface
     */
    public function createCustomerMultiFactorAuthStatusValidator(): MultiFactorAuthStatusValidatorInterface
    {
        return new CustomerMultiFactorAuthStatusValidator(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Validator\MultiFactorAuthStatusValidatorInterface
     */
    public function createUserMultiFactorAuthStatusValidator(): MultiFactorAuthStatusValidatorInterface
    {
        return new UserMultiFactorAuthStatusValidator(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Validator\CodeValidatorInterface
     */
    public function createCustomerCodeValidator(): CodeValidatorInterface
    {
        return new CustomerCodeValidator(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getGlossaryFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Validator\CodeValidatorInterface
     */
    public function createUserCodeValidator(): CodeValidatorInterface
    {
        return new UserCodeValidator(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getGlossaryFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): MultiFactorAuthToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Sender\CodeSenderInterface
     */
    public function createCustomerCodeSender(): CodeSenderInterface
    {
        return new CustomerCodeSender(
            $this->getEntityManager(),
            $this->createCustomerCodeGenerator(),
            $this->getCustomerSendStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Sender\CodeSenderInterface
     */
    public function createUserCodeSender(): CodeSenderInterface
    {
        return new UserCodeSender(
            $this->getEntityManager(),
            $this->createUserCodeGenerator(),
            $this->getUserSendStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGeneratorInterface
     */
    public function createCustomerCodeGenerator(): CodeGeneratorInterface
    {
        return new CodeGenerator(
            $this->createCustomerCodeGeneratorConfigProvider(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGeneratorInterface
     */
    public function createUserCodeGenerator(): CodeGeneratorInterface
    {
        return new CodeGenerator(
            $this->createUserCodeGeneratorConfigProvider(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Generator\Provider\CodeGeneratorConfigProviderInterface
     */
    public function createCustomerCodeGeneratorConfigProvider(): CodeGeneratorConfigProviderInterface
    {
        return new CustomerCodeGeneratorConfigProvider(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Generator\Provider\CodeGeneratorConfigProviderInterface
     */
    public function createUserCodeGeneratorConfigProvider(): CodeGeneratorConfigProviderInterface
    {
        return new UserCodeGeneratorConfigProvider(
            $this->getConfig(),
        );
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\SendStrategyPluginInterface>
     */
    public function getCustomerSendStrategyPlugins(): array
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::PLUGINS_CUSTOMER_SEND_STRATEGY);
    }

    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\SendStrategyPluginInterface>
     */
    public function getUserSendStrategyPlugins(): array
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::PLUGINS_USER_SEND_STRATEGY);
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Reader\User\UserMultiFactorAuthReaderInterface
     */
    public function createUserMultiFactorAuthReader(): UserMultiFactorAuthReaderInterface
    {
        return new UserMultiFactorAuthReader(
            $this->getRepository(),
        );
    }
}
