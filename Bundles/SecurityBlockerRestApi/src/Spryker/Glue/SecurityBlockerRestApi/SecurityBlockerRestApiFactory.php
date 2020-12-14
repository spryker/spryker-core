<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface;
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
        return new SecurityBlockerStorage($this->getSecurityBlockerRestApiDependencyProvider());
    }

    /**
     * @return \Spryker\Glue\SecurityBlockerRestApi\Processor\Customer\Validator\SecurityBlockerValidatorInterface
     */
    public function createSecurityBlockerValidator(): SecurityBlockerValidatorInterface
    {
        return new SecurityBlockerValidator($this->getSecurityBlockerRestApiDependencyProvider());
    }

    /**
     * @return \Spryker\Glue\SecurityBlockerRestApi\Dependency\Client\SecurityBlockerRestApiToSecurityBlockerClientInterface
     */
    public function getSecurityBlockerRestApiDependencyProvider(): SecurityBlockerRestApiToSecurityBlockerClientInterface
    {
        return $this->getProvidedDependency(SecurityBlockerRestApiDependencyProvider::CLIENT_SECURITY_BLOCKER);
    }
}
