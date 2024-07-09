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
use Spryker\Glue\OauthBackendApi\Processor\Builder\RequestBuilder;
use Spryker\Glue\OauthBackendApi\Processor\Builder\RequestBuilderInterface;
use Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractor;
use Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractorInterface;
use Spryker\Glue\OauthBackendApi\Processor\Extractor\BackendAccessTokenExtractor;
use Spryker\Glue\OauthBackendApi\Processor\Extractor\BackendAccessTokenExtractorInterface;
use Spryker\Glue\OauthBackendApi\Processor\Logger\AuditLogger;
use Spryker\Glue\OauthBackendApi\Processor\Logger\AuditLoggerInterface;
use Spryker\Glue\OauthBackendApi\Processor\Mapper\GlueRequestMapper;
use Spryker\Glue\OauthBackendApi\Processor\Mapper\GlueRequestMapperInterface;
use Spryker\Glue\OauthBackendApi\Processor\RequestBuilder\UserRequestBuilder;
use Spryker\Glue\OauthBackendApi\Processor\RequestBuilder\UserRequestBuilderInterface;
use Spryker\Glue\OauthBackendApi\Processor\Validator\AccessTokenValidator;
use Spryker\Glue\OauthBackendApi\Processor\Validator\AccessTokenValidatorInterface;
use Spryker\Glue\OauthBackendApi\Processor\Validator\BackendApiAccessTokenValidator;
use Spryker\Glue\OauthBackendApi\Processor\Validator\BackendApiAccessTokenValidatorInterface;
use Spryker\Glue\OauthBackendApi\Processor\Validator\UserRequestValidator;
use Spryker\Glue\OauthBackendApi\Processor\Validator\UserRequestValidatorInterface;

/**
 * @method \Spryker\Glue\OauthBackendApi\OauthBackendApiConfig getConfig()
 */
class OauthBackendApiFactory extends AbstractBackendApiFactory
{
    /**
     * @deprecated Use {@link \Spryker\Glue\OauthBackendApi\OauthBackendApiFactory::createBackendApiAccessTokenValidator()} instead.
     *
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
     * @return \Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractorInterface
     */
    public function createAccessTokenExtractor(): AccessTokenExtractorInterface
    {
        return new AccessTokenExtractor();
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Processor\Extractor\BackendAccessTokenExtractorInterface
     */
    public function createBackendAccessTokenExtractor(): BackendAccessTokenExtractorInterface
    {
        return new BackendAccessTokenExtractor();
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Processor\Builder\RequestBuilderInterface
     */
    public function createRequestBuilder(): RequestBuilderInterface
    {
        return new RequestBuilder(
            $this->getOauthService(),
            $this->createGlueRequestMapper(),
            $this->createAccessTokenExtractor(),
        );
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Processor\Mapper\GlueRequestMapperInterface
     */
    public function createGlueRequestMapper(): GlueRequestMapperInterface
    {
        return new GlueRequestMapper(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @deprecated Use {@link \Spryker\Glue\OauthBackendApi\OauthBackendApiFactory::createRequestBuilder()} instead.
     *
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

    /**
     * @return \Spryker\Glue\OauthBackendApi\Processor\Validator\UserRequestValidatorInterface
     */
    public function createUserRequestValidator(): UserRequestValidatorInterface
    {
        return new UserRequestValidator(
            $this->getUserRequestValidationPreCheckerPlugins(),
        );
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Processor\Logger\AuditLoggerInterface
     */
    public function createAuditLogger(): AuditLoggerInterface
    {
        return new AuditLogger();
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
     * @return \Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToOauthFacadeInterface
     */
    public function getOauthFacade(): OauthBackendApiToOauthFacadeInterface
    {
        return $this->getProvidedDependency(OauthBackendApiDependencyProvider::FACADE_OAUTH);
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Dependency\Facade\OauthBackendApiToAuthenticationFacadeInterface
     */
    public function getAuthenticationFacade(): OauthBackendApiToAuthenticationFacadeInterface
    {
        return $this->getProvidedDependency(OauthBackendApiDependencyProvider::FACADE_AUTHENTICATION);
    }

    /**
     * @return list<\Spryker\Glue\OauthBackendApiExtension\Dependency\Plugin\UserRequestValidationPreCheckerPluginInterface>
     */
    public function getUserRequestValidationPreCheckerPlugins(): array
    {
        return $this->getProvidedDependency(OauthBackendApiDependencyProvider::PLUGINS_USER_REQUEST_VALIDATION_PRE_CHECKER);
    }

    /**
     * @return \Spryker\Glue\OauthBackendApi\Processor\Validator\BackendApiAccessTokenValidatorInterface
     */
    public function createBackendApiAccessTokenValidator(): BackendApiAccessTokenValidatorInterface
    {
        return new BackendApiAccessTokenValidator(
            $this->getOauthFacade(),
            $this->createBackendAccessTokenExtractor(),
        );
    }
}
