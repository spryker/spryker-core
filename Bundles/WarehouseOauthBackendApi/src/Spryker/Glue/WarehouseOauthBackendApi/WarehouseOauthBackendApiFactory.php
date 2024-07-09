<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToAuthenticationFacadeInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToWarehouseUserFacadeInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToOauthServiceInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToUtilEncodingServiceInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Creator\WarehouseTokenCreator;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Creator\WarehouseTokenCreatorInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Expander\WarehouseAuthorizationRequestExpander;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Logger\AuditLogger;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Logger\AuditLoggerInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\GlueRequestReader;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\GlueRequestReaderInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\WarehouseUserAssignmentReader;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\WarehouseUserAssignmentReaderInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\RequestBuilder\WarehouseRequestBuilder;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\RequestBuilder\WarehouseRequestBuilderInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\ResponseBuilder\WarehouseResponseBuilder;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\ResponseBuilder\WarehouseResponseBuilderInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Validator\WarehouseRequestValidator;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Validator\WarehouseRequestValidatorInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Validator\WarehouseUserRequestValidator;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Validator\WarehouseUserRequestValidatorInterface;

/**
 * @method \Spryker\Glue\WarehouseOauthBackendApi\WarehouseOauthBackendApiConfig getConfig()
 */
class WarehouseOauthBackendApiFactory extends AbstractBackendApiFactory
{
    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\Creator\WarehouseTokenCreatorInterface
     */
    public function createWarehouseTokenCreator(): WarehouseTokenCreatorInterface
    {
        return new WarehouseTokenCreator(
            $this->createWarehouseUserAssignmentReader(),
            $this->getAuthenticationFacade(),
            $this->createWarehouseResponseBuilder(),
            $this->createAuditLogger(),
        );
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\RequestBuilder\WarehouseRequestBuilderInterface
     */
    public function createWarehouseRequestBuilder(): WarehouseRequestBuilderInterface
    {
        return new WarehouseRequestBuilder(
            $this->getUtilEncodingService(),
            $this->createGlueRequestReader(),
        );
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\ResponseBuilder\WarehouseResponseBuilderInterface
     */
    public function createWarehouseResponseBuilder(): WarehouseResponseBuilderInterface
    {
        return new WarehouseResponseBuilder();
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\Expander\WarehouseAuthorizationRequestExpander
     */
    public function createWarehouseAuthorizationRequestExpander(): WarehouseAuthorizationRequestExpander
    {
        return new WarehouseAuthorizationRequestExpander();
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\GlueRequestReaderInterface
     */
    public function createGlueRequestReader(): GlueRequestReaderInterface
    {
        return new GlueRequestReader(
            $this->getOauthService(),
        );
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\Validator\WarehouseRequestValidatorInterface
     */
    public function createWarehouseRequestValidator(): WarehouseRequestValidatorInterface
    {
        return new WarehouseRequestValidator();
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\Validator\WarehouseUserRequestValidatorInterface
     */
    public function createWarehouseUserRequestValidator(): WarehouseUserRequestValidatorInterface
    {
        return new WarehouseUserRequestValidator();
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\WarehouseUserAssignmentReaderInterface
     */
    public function createWarehouseUserAssignmentReader(): WarehouseUserAssignmentReaderInterface
    {
        return new WarehouseUserAssignmentReader(
            $this->getWarehouseUserFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Processor\Logger\AuditLoggerInterface
     */
    public function createAuditLogger(): AuditLoggerInterface
    {
        return new AuditLogger();
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToAuthenticationFacadeInterface
     */
    public function getAuthenticationFacade(): WarehouseOauthBackendApiToAuthenticationFacadeInterface
    {
        return $this->getProvidedDependency(WarehouseOauthBackendApiDependencyProvider::FACADE_AUTHENTICATION);
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Facade\WarehouseOauthBackendApiToWarehouseUserFacadeInterface
     */
    public function getWarehouseUserFacade(): WarehouseOauthBackendApiToWarehouseUserFacadeInterface
    {
        return $this->getProvidedDependency(WarehouseOauthBackendApiDependencyProvider::FACADE_WAREHOUSE_USER);
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToOauthServiceInterface
     */
    public function getOauthService(): WarehouseOauthBackendApiToOauthServiceInterface
    {
        return $this->getProvidedDependency(WarehouseOauthBackendApiDependencyProvider::SERVICE_OAUTH);
    }

    /**
     * @return \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): WarehouseOauthBackendApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(WarehouseOauthBackendApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
