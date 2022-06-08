<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToAuthenticationFacadeInterface;
use Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToOauthFacadeInterface;
use Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToOauthServiceInterface;
use Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToUtilEncodingServiceInterface;
use Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractor;
use Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractorInterface;
use Spryker\Glue\OauthBackendApi\Processor\RequestBuilder\UserRequestBuilder;
use Spryker\Glue\OauthBackendApi\Processor\RequestBuilder\UserRequestBuilderInterface;
use Spryker\Glue\OauthBackendApi\Processor\Validator\AccessTokenValidator;
use Spryker\Glue\OauthBackendApi\Processor\Validator\AccessTokenValidatorInterface;

/**
 * @method \Spryker\Glue\OauthBackendApi\OauthBackendApiConfig getConfig()
 */
class OauthBackendApiFactory extends AbstractBackendApiFactory
{
    /**
     * @return \Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToAuthenticationFacadeInterface
     */
    public function getAuthenticationFacade(): OauthBackendApiToAuthenticationFacadeInterface
    {
        return $this->getProvidedDependency(OauthBackendApiDependencyProvider::FACADE_AUTHENTICATION);
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Processor\Validator\AccessTokenValidatorInterface
     */
    public function createAccessTokenValidator(): AccessTokenValidatorInterface
    {
        return new AccessTokenValidator(
            $this->getOauthFacade(),
            $this->createAccessTokenExtractor(),
        );
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToOauthServiceInterface
     */
    public function getOauthService(): OauthBackendApiToOauthServiceInterface
    {
        return $this->getProvidedDependency(OauthBackendApiDependencyProvider::SERVICE_OAUTH);
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OauthBackendApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OauthBackendApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractorInterface
     */
    public function createAccessTokenExtractor(): AccessTokenExtractorInterface
    {
        return new AccessTokenExtractor();
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToOauthFacadeInterface
     */
    public function getOauthFacade(): OauthBackendApiToOauthFacadeInterface
    {
        return $this->getProvidedDependency(OauthBackendApiDependencyProvider::FACADE_OAUTH);
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Processor\RequestBuilder\UserRequestBuilderInterface
     */
    public function createUserRequestBuilder(): UserRequestBuilderInterface
    {
        return new UserRequestBuilder(
            $this->getOauthService(),
            $this->getUtilEncodingService(),
            $this->createAccessTokenExtractor(),
        );
    }
}
