<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCustomerValidation;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\OauthCustomerValidation\Dependency\Client\OauthCustomerValidationToCustomerStorageClientInterface;
use Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToOauthServiceInterface;
use Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToUtilEncodingServiceInterface;
use Spryker\Client\OauthCustomerValidation\Mapper\OauthCustomerValidationMapper;
use Spryker\Client\OauthCustomerValidation\Mapper\OauthCustomerValidationMapperInterface;
use Spryker\Client\OauthCustomerValidation\Validator\InvalidatedCustomerAccessTokenValidator;
use Spryker\Client\OauthCustomerValidation\Validator\InvalidatedCustomerAccessTokenValidatorInterface;

class OauthCustomerValidationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\OauthCustomerValidation\Validator\InvalidatedCustomerAccessTokenValidatorInterface
     */
    public function createInvalidatedCustomerAccessTokenValidator(): InvalidatedCustomerAccessTokenValidatorInterface
    {
        return new InvalidatedCustomerAccessTokenValidator(
            $this->createOauthCustomerValidationMapper(),
            $this->getCustomerStorageClient(),
            $this->getOauthService(),
        );
    }

    /**
     * @return \Spryker\Client\OauthCustomerValidation\Mapper\OauthCustomerValidationMapperInterface
     */
    public function createOauthCustomerValidationMapper(): OauthCustomerValidationMapperInterface
    {
        return new OauthCustomerValidationMapper(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Client\OauthCustomerValidation\Dependency\Client\OauthCustomerValidationToCustomerStorageClientInterface
     */
    public function getCustomerStorageClient(): OauthCustomerValidationToCustomerStorageClientInterface
    {
        return $this->getProvidedDependency(OauthCustomerValidationDependencyProvider::CLIENT_CUSTOMER_STORAGE);
    }

    /**
     * @return \Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OauthCustomerValidationToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OauthCustomerValidationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\OauthCustomerValidation\Dependency\Service\OauthCustomerValidationToOauthServiceInterface
     */
    public function getOauthService(): OauthCustomerValidationToOauthServiceInterface
    {
        return $this->getProvidedDependency(OauthCustomerValidationDependencyProvider::SERVICE_OAUTH);
    }
}
