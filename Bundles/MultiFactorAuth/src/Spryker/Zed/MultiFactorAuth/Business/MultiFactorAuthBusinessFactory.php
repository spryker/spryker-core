<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGenerator;
use Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGeneratorInterface;
use Spryker\Zed\MultiFactorAuth\Business\Sender\CodeSenderInterface;
use Spryker\Zed\MultiFactorAuth\Business\Sender\Customer\CustomerCodeSender;
use Spryker\Zed\MultiFactorAuth\Business\Strategy\Customer\CustomerEmailCodeSenderStrategy;
use Spryker\Zed\MultiFactorAuth\Business\Strategy\SendStrategyInterface;
use Spryker\Zed\MultiFactorAuth\Business\Validator\CodeValidatorInterface;
use Spryker\Zed\MultiFactorAuth\Business\Validator\Customer\CustomerCodeValidator;
use Spryker\Zed\MultiFactorAuth\Business\Validator\Customer\CustomerMultiFactorAuthStatusValidator;
use Spryker\Zed\MultiFactorAuth\Business\Validator\MultiFactorAuthStatusValidatorInterface;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToGlossaryFacadeInterface;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMailFacadeInterface;
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
            $this->createCodeGenerator(),
            $this->getCustomerCodeSenderStrategies(),
        );
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Generator\CodeGeneratorInterface
     */
    public function createCodeGenerator(): CodeGeneratorInterface
    {
        return new CodeGenerator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMailFacadeInterface
     */
    public function getMailFacade(): MultiFactorAuthToMailFacadeInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return array<\Spryker\Zed\MultiFactorAuth\Business\Strategy\SendStrategyInterface>
     */
    public function getCustomerCodeSenderStrategies(): array
    {
        return [
            $this->createCustomerEmailCodeSenderStrategy(),
        ];
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Business\Strategy\SendStrategyInterface
     */
    public function createCustomerEmailCodeSenderStrategy(): SendStrategyInterface
    {
        return new CustomerEmailCodeSenderStrategy($this->getMailFacade());
    }
}
