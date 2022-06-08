<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention;

use Spryker\Glue\GlueRestApiConvention\Dependency\External\GlueRestApiConventionToInflectorInterface;
use Spryker\Glue\GlueRestApiConvention\Dependency\Service\GlueRestApiConventionToUtilEncodingServiceInterface;
use Spryker\Glue\GlueRestApiConvention\Formatter\RestApiSchemaFormatter;
use Spryker\Glue\GlueRestApiConvention\Formatter\RestApiSchemaParametersFormatter;
use Spryker\Glue\GlueRestApiConvention\Formatter\RestApiSchemaParametersFormatterInterface;
use Spryker\Glue\GlueRestApiConvention\Formatter\SchemaFormatterInterface;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\AttributesRequestBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestBuilderInterface;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestFilterFieldBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestFormatBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestPaginationParameterBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestSortParameterBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestSparseFieldBuilder;
use Spryker\Glue\GlueRestApiConvention\RequestValidator\AcceptedFormatValidator;
use Spryker\Glue\GlueRestApiConvention\RequestValidator\AcceptedFormatValidatorInterface;
use Spryker\Glue\GlueRestApiConvention\ResponseBuilder\ResponseContentBuilder;
use Spryker\Glue\GlueRestApiConvention\ResponseBuilder\ResponseContentBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig getConfig()
 */
class GlueRestApiConventionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestBuilderInterface
     */
    public function createRequestPaginationParameterBuilder(): RequestBuilderInterface
    {
        return new RequestPaginationParameterBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestBuilderInterface
     */
    public function createRequestSortParameterBuilder(): RequestBuilderInterface
    {
        return new RequestSortParameterBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\Dependency\Service\GlueRestApiConventionToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): GlueRestApiConventionToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\ResponseBuilder\ResponseContentBuilderInterface
     */
    public function createResponseContentBuilder(): ResponseContentBuilderInterface
    {
        return new ResponseContentBuilder(
            $this->getResponseEncoderPlugins(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\Formatter\SchemaFormatterInterface
     */
    public function createRestApiSchemaFormatter(): SchemaFormatterInterface
    {
        return new RestApiSchemaFormatter(
            $this->getInflector(),
            $this->createRestApiSchemaParametersFormatter(),
            $this->getConfig(),
        );
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface>
     */
    public function getResponseEncoderPlugins(): array
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::PLUGINS_RESPONSE_ENCODER);
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    public function getRequestBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::PLUGINS_REQUEST_BUILDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function getRequestValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::PLUGINS_REQUEST_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    public function getRequestAfterRoutingValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    public function getResponseFormatterPlugins(): array
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::PLUGINS_RESPONSE_FORMATTER);
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestBuilderInterface
     */
    public function createRequestFormatBuilder(): RequestBuilderInterface
    {
        return new RequestFormatBuilder($this->getResponseEncoderPlugins());
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestValidator\AcceptedFormatValidatorInterface
     */
    public function createAcceptedFormatValidator(): AcceptedFormatValidatorInterface
    {
        return new AcceptedFormatValidator($this->getResponseEncoderPlugins());
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestBuilderInterface
     */
    public function createRequestFilterFieldBuilder(): RequestBuilderInterface
    {
        return new RequestFilterFieldBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestBuilderInterface
     */
    public function createRequestSparseFieldBuilder(): RequestBuilderInterface
    {
        return new RequestSparseFieldBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestBuilderInterface
     */
    public function createAttributesRequestBuilder(): RequestBuilderInterface
    {
        return new AttributesRequestBuilder(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\Dependency\External\GlueRestApiConventionToInflectorInterface
     */
    public function getInflector(): GlueRestApiConventionToInflectorInterface
    {
        return $this->getProvidedDependency(GlueRestApiConventionDependencyProvider::INFLECTOR);
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\Formatter\RestApiSchemaParametersFormatterInterface
     */
    public function createRestApiSchemaParametersFormatter(): RestApiSchemaParametersFormatterInterface
    {
        return new RestApiSchemaParametersFormatter();
    }
}
