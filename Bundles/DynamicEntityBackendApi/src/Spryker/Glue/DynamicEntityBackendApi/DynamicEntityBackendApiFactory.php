<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Spryker\Glue\DynamicEntityBackendApi\Builder\Route\RouteBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Builder\Route\RouteBuilderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToLocaleClientInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToStorageFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceInterface;
use Spryker\Glue\DynamicEntityBackendApi\Expander\DocumentationSchemaExpander;
use Spryker\Glue\DynamicEntityBackendApi\Expander\DocumentationSchemaExpanderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Expander\DynamicEntityProtectedPathCollectionExpander;
use Spryker\Glue\DynamicEntityBackendApi\Expander\DynamicEntityProtectedPathCollectionExpanderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathGetMethodBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathMethodBuilderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathPatchMethodBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathPostMethodBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathPutMethodBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\DynamicApiPathMethodFormatter;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\DynamicApiPathMethodFormatterInterface;
use Spryker\Glue\DynamicEntityBackendApi\InvalidationVoter\InvalidationVoter;
use Spryker\Glue\DynamicEntityBackendApi\InvalidationVoter\InvalidationVoterInterface;
use Spryker\Glue\DynamicEntityBackendApi\Logger\DynamicEntityBackendApiLogger;
use Spryker\Glue\DynamicEntityBackendApi\Logger\DynamicEntityBackendApiLoggerInterface;
use Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueRequestDynamicEntityMapper;
use Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueResponseDynamicEntityMapper;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Creator\DynamicEntityCreator;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Creator\DynamicEntityCreatorInterface;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Reader\DynamicEntityReader;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Reader\DynamicEntityReaderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Updater\DynamicEntityUpdater;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Updater\DynamicEntityUpdaterInterface;
use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;

/**
 * @method \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig getConfig()
 */
class DynamicEntityBackendApiFactory extends AbstractBackendApiFactory
{
    /**
     * @var string
     */
    protected const LOGGER_NAME = 'dynamicEntityLogger';

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface
     */
    public function getDynamicEntityFacade(): DynamicEntityBackendApiToDynamicEntityFacadeInterface
    {
        return $this->getProvidedDependency(DynamicEntityBackendApiDependencyProvider::FACADE_DYNAMIC_ENTITY);
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceInterface
     */
    public function getServiceUtilEncoding(): DynamicEntityBackendApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(DynamicEntityBackendApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Processor\Reader\DynamicEntityReaderInterface
     */
    public function createDynamicEntityReader(): DynamicEntityReaderInterface
    {
        return new DynamicEntityReader(
            $this->getDynamicEntityFacade(),
            $this->createGlueRequestDynamicEntityMapper(),
            $this->createGlueResponseDynamicEntityMapper(),
            $this->createDynamicEntityLogger(),
        );
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Processor\Creator\DynamicEntityCreatorInterface
     */
    public function createDynamicEntityCreator(): DynamicEntityCreatorInterface
    {
        return new DynamicEntityCreator(
            $this->getDynamicEntityFacade(),
            $this->createGlueRequestDynamicEntityMapper(),
            $this->createGlueResponseDynamicEntityMapper(),
            $this->createDynamicEntityLogger(),
        );
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Processor\Updater\DynamicEntityUpdaterInterface
     */
    public function createDynamicEntityUpdater(): DynamicEntityUpdaterInterface
    {
        return new DynamicEntityUpdater(
            $this->getDynamicEntityFacade(),
            $this->createGlueRequestDynamicEntityMapper(),
            $this->createGlueResponseDynamicEntityMapper(),
            $this->createDynamicEntityLogger(),
        );
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueRequestDynamicEntityMapper
     */
    public function createGlueRequestDynamicEntityMapper(): GlueRequestDynamicEntityMapper
    {
        return new GlueRequestDynamicEntityMapper(
            $this->getServiceUtilEncoding(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueResponseDynamicEntityMapper
     */
    public function createGlueResponseDynamicEntityMapper(): GlueResponseDynamicEntityMapper
    {
        return new GlueResponseDynamicEntityMapper(
            $this->getServiceUtilEncoding(),
            $this->getGlossaryStorageClient(),
            $this->getLocaleClient(),
        );
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Builder\Route\RouteBuilderInterface
     */
    public function createRouteBuilder(): RouteBuilderInterface
    {
        return new RouteBuilder(
            $this->getDynamicEntityFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): DynamicEntityBackendApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(DynamicEntityBackendApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToLocaleClientInterface
     */
    public function getLocaleClient(): DynamicEntityBackendApiToLocaleClientInterface
    {
        return $this->getProvidedDependency(DynamicEntityBackendApiDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Logger\DynamicEntityBackendApiLoggerInterface
     */
    public function createDynamicEntityLogger(): DynamicEntityBackendApiLoggerInterface
    {
        return new DynamicEntityBackendApiLogger(
            $this->createLogger(),
        );
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Expander\DocumentationSchemaExpanderInterface
     */
    public function createDocumentationSchemaExpander(): DocumentationSchemaExpanderInterface
    {
        return new DocumentationSchemaExpander($this->createDynamicEntityReader());
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    public function createLogger(): ?LoggerInterface
    {
        if (!$this->getConfig()->isLoggingEnabled()) {
            return null;
        }

        return new Logger(static::LOGGER_NAME, [
            $this->createBufferedStreamHandler(),
        ]);
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createBufferedStreamHandler(): HandlerInterface
    {
        return new BufferHandler(
            $this->createStreamHandler(),
        );
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createStreamHandler(): HandlerInterface
    {
        return new StreamHandler($this->getConfig()->getLogFilepath());
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Expander\DynamicEntityProtectedPathCollectionExpanderInterface
     */
    public function createDynamicEntityProtectedPathCollectionExpander(): DynamicEntityProtectedPathCollectionExpanderInterface
    {
        return new DynamicEntityProtectedPathCollectionExpander($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Formatter\DynamicApiPathMethodFormatterInterface
     */
    public function createDynamicApiPathMethodFormatter(): DynamicApiPathMethodFormatterInterface
    {
        return new DynamicApiPathMethodFormatter($this->getPathMethodBuilders());
    }

    /**
     * @return array<\Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathMethodBuilderInterface>
     */
    public function getPathMethodBuilders(): array
    {
        return [
            $this->createPathGetMethodBuilder(),
            $this->createPathPostMethodBuilder(),
            $this->createPathPutMethodBuilder(),
            $this->createPathPatchMethodBuilder(),
        ];
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathMethodBuilderInterface
     */
    public function createPathGetMethodBuilder(): PathMethodBuilderInterface
    {
        return new PathGetMethodBuilder($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathMethodBuilderInterface
     */
    public function createPathPostMethodBuilder(): PathMethodBuilderInterface
    {
        return new PathPostMethodBuilder($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathMethodBuilderInterface
     */
    public function createPathPutMethodBuilder(): PathMethodBuilderInterface
    {
        return new PathPutMethodBuilder($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathMethodBuilderInterface
     */
    public function createPathPatchMethodBuilder(): PathMethodBuilderInterface
    {
        return new PathPatchMethodBuilder($this->getConfig());
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\InvalidationVoter\InvalidationVoterInterface
     */
    public function createInvalidationVoter(): InvalidationVoterInterface
    {
        return new InvalidationVoter(
            $this->getDynamicEntityFacade(),
            $this->getConfig(),
            $this->getStorageFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToStorageFacadeInterface
     */
    public function getStorageFacade(): DynamicEntityBackendApiToStorageFacadeInterface
    {
        return $this->getProvidedDependency(DynamicEntityBackendApiDependencyProvider::FACADE_STORAGE);
    }
}
