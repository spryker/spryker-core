<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention;

use Spryker\Glue\GlueJsonApiConvention\Decoder\DecoderInterface;
use Spryker\Glue\GlueJsonApiConvention\Decoder\JsonDecoder;
use Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface;
use Spryker\Glue\GlueJsonApiConvention\Encoder\EncoderInterface;
use Spryker\Glue\GlueJsonApiConvention\Encoder\JsonEncoder;
use Spryker\Glue\GlueJsonApiConvention\Request\AttributesRequestBuilder;
use Spryker\Glue\GlueJsonApiConvention\Request\RequestBuilderInterface;
use Spryker\Glue\GlueJsonApiConvention\Request\RequestFilterFieldBuilder;
use Spryker\Glue\GlueJsonApiConvention\Request\RequestPaginationParameterBuilder;
use Spryker\Glue\GlueJsonApiConvention\Request\RequestRelationshipBuilder;
use Spryker\Glue\GlueJsonApiConvention\Request\RequestSortParameterBuilder;
use Spryker\Glue\GlueJsonApiConvention\Request\RequestSparseFieldBuilder;
use Spryker\Glue\GlueJsonApiConvention\Resource\ResourceRelationshipLoader;
use Spryker\Glue\GlueJsonApiConvention\Resource\ResourceRelationshipLoaderInterface;
use Spryker\Glue\GlueJsonApiConvention\Response\JsonApiResponseBuilder;
use Spryker\Glue\GlueJsonApiConvention\Response\JsonApiResponseBuilderInterface;
use Spryker\Glue\GlueJsonApiConvention\Response\JsonGlueResponseFormatter;
use Spryker\Glue\GlueJsonApiConvention\Response\JsonGlueResponseFormatterInterface;
use Spryker\Glue\GlueJsonApiConvention\Response\RelationshipResponseBuilder;
use Spryker\Glue\GlueJsonApiConvention\Response\RelationshipResponseBuilderInterface;
use Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatter;
use Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatterInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig getConfig()
 */
class GlueJsonApiConventionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Request\RequestBuilderInterface
     */
    public function createRequestPaginationParameterBuilder(): RequestBuilderInterface
    {
        return new RequestPaginationParameterBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Request\RequestBuilderInterface
     */
    public function createRequestSortParameterBuilder(): RequestBuilderInterface
    {
        return new RequestSortParameterBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Request\RequestBuilderInterface
     */
    public function createRequestFilterFieldBuilder(): RequestBuilderInterface
    {
        return new RequestFilterFieldBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Request\RequestBuilderInterface
     */
    public function createRequestSparseFieldBuilder(): RequestBuilderInterface
    {
        return new RequestSparseFieldBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Request\RequestBuilderInterface
     */
    public function createRequestRelationshipBuilder(): RequestBuilderInterface
    {
        return new RequestRelationshipBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Decoder\DecoderInterface
     */
    public function createJsonDecoder(): DecoderInterface
    {
        return new JsonDecoder($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Encoder\EncoderInterface
     */
    public function createJsonEncoder(): EncoderInterface
    {
        return new JsonEncoder($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Response\JsonGlueResponseFormatterInterface
     */
    public function createJsonGlueResponseFormatter(): JsonGlueResponseFormatterInterface
    {
        return new JsonGlueResponseFormatter(
            $this->createJsonEncoder(),
            $this->getConfig(),
            $this->createResponseSparseFieldFormatter(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Response\JsonApiResponseBuilderInterface
     */
    public function createJsonApiResponseBuilder(): JsonApiResponseBuilderInterface
    {
        return new JsonApiResponseBuilder($this->createJsonGlueResponseFormatter());
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Response\RelationshipResponseBuilderInterface
     */
    public function createRelationshipResponseBuilder(): RelationshipResponseBuilderInterface
    {
        return new RelationshipResponseBuilder($this->createResourceRelationshipLoader());
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Resource\ResourceRelationshipLoaderInterface
     */
    public function createResourceRelationshipLoader(): ResourceRelationshipLoaderInterface
    {
        return new ResourceRelationshipLoader($this->getRelationshipProviderPlugins());
    }

    /**
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RelationshipProviderPluginInterface>
     */
    public function getRelationshipProviderPlugins(): array
    {
        return $this->getProvidedDependency(GlueJsonApiConventionDependencyProvider::PLUGINS_RELATIONSHIP_PROVIDER);
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): GlueJsonApiConventionToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(GlueJsonApiConventionDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    public function getRequestBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GlueJsonApiConventionDependencyProvider::PLUGINS_REQUEST_BUILDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function getRequestValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueJsonApiConventionDependencyProvider::PLUGINS_REQUEST_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    public function getRequestAfterRoutingValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueJsonApiConventionDependencyProvider::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    public function getResponseFormatterPlugins(): array
    {
        return $this->getProvidedDependency(GlueJsonApiConventionDependencyProvider::PLUGINS_RESPONSE_FORMATTER);
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Request\RequestBuilderInterface
     */
    public function createAttributesRequestBuilder(): RequestBuilderInterface
    {
        return new AttributesRequestBuilder($this->createJsonDecoder());
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatterInterface
     */
    public function createResponseSparseFieldFormatter(): ResponseSparseFieldFormatterInterface
    {
        return new ResponseSparseFieldFormatter();
    }
}
