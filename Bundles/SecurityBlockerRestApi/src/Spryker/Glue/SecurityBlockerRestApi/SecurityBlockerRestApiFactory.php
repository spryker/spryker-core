<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Agent\Storage\SecurityBlockerAgentStorage;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Agent\Storage\SecurityBlockerAgentStorageInterface;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Agent\Validator\SecurityBlockerAgentValidator;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Agent\Validator\SecurityBlockerAgentValidatorInterface;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Builder\RestErrorCollectionBuilder;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Builder\RestErrorCollectionBuilderInterface;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Checker\AuthenticationChecker;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Checker\AuthenticationCheckerInterface;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Customer\Storage\SecurityBlockerStorage;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Customer\Storage\SecurityBlockerStorageInterface;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Customer\Validator\SecurityBlockerValidator;
use Spryker\Glue\SecurityBlockerRestApi\Processor\Customer\Validator\SecurityBlockerValidatorInterface;

class SecurityBlockerRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\SecurityBlockerRestApi\Processor\Customer\Storage\SecurityBlockerStorageInterface
     */
    public function createSecurityBlockerStorage(): SecurityBlockerStorageInterface
    {
        return new SecurityBlockerStorage(
            $this->getSecurityBlockerClient(),
            $this->createAuthenticationChecker()
        );
    }

    /**
     * @return \Spryker\Glue\SecurityBlockerRestApi\Processor\Customer\Validator\SecurityBlockerValidatorInterface
     */
    public function createSecurityBlockerValidator(): SecurityBlockerValidatorInterface
    {
        return new SecurityBlockerValidator(
            $this->getSecurityBlockerClient(),
            $this->createAuthenticationChecker(),
            $this->createRestErrorCollectionBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\SecurityBlockerRestApi\Processor\Agent\Storage\SecurityBlockerAgentStorageInterface
     */
    public function createSecurityBlockerAgentStorage(): SecurityBlockerAgentStorageInterface
    {
        return new SecurityBlockerAgentStorage(
            $this->getSecurityBlockerClient(),
            $this->createAuthenticationChecker()
        );
    }

    /**
     * @return \Spryker\Glue\SecurityBlockerRestApi\Processor\Agent\Validator\SecurityBlockerAgentValidatorInterface
     */
    public function createSecurityBlockerAgentValidator(): SecurityBlockerAgentValidatorInterface
    {
        return new SecurityBlockerAgentValidator(
            $this->getSecurityBlockerClient(),
            $this->createAuthenticationChecker(),
            $this->createRestErrorCollectionBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\SecurityBlockerRestApi\Processor\Builder\RestErrorCollectionBuilderInterface
     */
    public function createRestErrorCollectionBuilder(): RestErrorCollectionBuilderInterface
    {
        return new RestErrorCollectionBuilder($this->getGlossaryStorageClient());
    }

    /**
     * @return \Spryker\Glue\SecurityBlockerRestApi\Processor\Checker\AuthenticationCheckerInterface
     */
    public function createAuthenticationChecker(): AuthenticationCheckerInterface
    {
        return new AuthenticationChecker();
    }

    /**
     * @return \Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface
     */
    public function getSecurityBlockerClient(): SecurityBlockerRestApiToSecurityBlockerClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerRestApiDependencyProvider::CLIENT_SECURITY_BLOCKER);
    }

    /**
     * @return \Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): SecurityBlockerRestApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerRestApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }
}
