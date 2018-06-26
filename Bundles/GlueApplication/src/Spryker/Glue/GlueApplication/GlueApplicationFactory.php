<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication;

use Negotiation\LanguageNegotiator;
use Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface;
use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface;
use Spryker\Glue\GlueApplication\Plugin\Rest\GlueControllerListenerPlugin;
use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolver;
use Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface;
use Spryker\Glue\GlueApplication\Rest\ControllerCallbacks;
use Spryker\Glue\GlueApplication\Rest\ControllerCallbacksInterface;
use Spryker\Glue\GlueApplication\Rest\ControllerFilter;
use Spryker\Glue\GlueApplication\Rest\ControllerFilterInterface;
use Spryker\Glue\GlueApplication\Rest\Cors\CorsResponse;
use Spryker\Glue\GlueApplication\Rest\Cors\CorsResponseInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiation;
use Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RequestFormatter;
use Spryker\Glue\GlueApplication\Rest\Request\RequestFormatterInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractor;
use Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface;
use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoader;
use Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoader;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouter;
use Spryker\Glue\GlueApplication\Rest\ResourceRouterInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilder;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatter;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatterInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseHeaders;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseHeadersInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponsePagination;
use Spryker\Glue\GlueApplication\Rest\Response\ResponsePaginationInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationship;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationshipInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\Decoder\DecoderInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\Decoder\JsonDecoder;
use Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcher;
use Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\Encoder\EncoderInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\Encoder\JsonEncoder;
use Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcher;
use Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface;
use Spryker\Glue\GlueApplication\Rest\Uri\UriParser;
use Spryker\Glue\GlueApplication\Rest\Uri\UriParserInterface;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolver;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\Kernel\Application;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationConfig getConfig()
 */
class GlueApplicationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ControllerFilterInterface
     */
    public function createControllerFilter(): ControllerFilterInterface
    {
        return new ControllerFilter(
            $this->createRestRequestFormatter(),
            $this->createResponseFormatter(),
            $this->createResponseHeaders(),
            $this->createHttpRequestValidator(),
            $this->createRestRequestValidator(),
            $this->createRestResourceBuilder(),
            $this->createControllerCallbacks(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestFormatterInterface
     */
    protected function createRestRequestFormatter(): RequestFormatterInterface
    {
        return new RequestFormatter(
            $this->createRequestMetaDataExtractor(),
            $this->createDecoderMatcher(),
            $this->getResourceBuilder(),
            $this->getFormatRequestPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatterInterface
     */
    protected function createResponseFormatter(): ResponseFormatterInterface
    {
        return new ResponseFormatter(
            $this->createEncoderMatcher(),
            $this->createResponseBuilder(),
            $this->getFormatResponseDataPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface
     */
    protected function createResourceRelationshipLoader(): ResourceRelationshipLoaderInterface
    {
        return new ResourceRelationshipLoader($this->getResourceProviderPlugins());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouterInterface
     */
    public function createResourceRouter(): ResourceRouterInterface
    {
        return new ResourceRouter(
            $this->createHttpRequestValidator(),
            $this->getGlueApplication(),
            $this->createUriParser(),
            $this->createResourceRouteLoader()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface
     */
    protected function createResponseBuilder(): ResponseBuilderInterface
    {
        return new ResponseBuilder(
            $this->getConfig()->getGlueDomainName(),
            $this->createResponsePagination(),
            $this->createResponseRelationship()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseHeadersInterface
     */
    protected function createResponseHeaders(): ResponseHeadersInterface
    {
        return new ResponseHeaders(
            $this->getFormatResponseHeadersPlugins(),
            $this->createContentTypeResolver(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface
     */
    protected function createRequestMetaDataExtractor(): RequestMetaDataExtractorInterface
    {
        return new RequestMetaDataExtractor(
            $this->createVersionResolver(),
            $this->createContentTypeResolver(),
            $this->createLanguageNegotiation()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface
     */
    protected function createDecoderMatcher(): DecoderMatcherInterface
    {
        return new DecoderMatcher([
            DecoderMatcher::DEFAULT_FORMAT => $this->createJsonDecoder(),
        ]);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface
     */
    protected function createEncoderMatcher(): EncoderMatcherInterface
    {
        return new EncoderMatcher([
            EncoderMatcher::DEFAULT_FORMAT => $this->createJsonEncoder(),
        ]);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Serialize\Encoder\EncoderInterface
     */
    protected function createJsonEncoder(): EncoderInterface
    {
        return new JsonEncoder($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Serialize\Decoder\DecoderInterface
     */
    protected function createJsonDecoder(): DecoderInterface
    {
        return new JsonDecoder($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Uri\UriParserInterface
     */
    protected function createUriParser(): UriParserInterface
    {
        return new UriParser();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface
     */
    public function createResourceRouteLoader(): ResourceRouteLoaderInterface
    {
        return new ResourceRouteLoader($this->getResourceRoutePlugins(), $this->createVersionResolver());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface
     */
    protected function createHttpRequestValidator(): HttpRequestValidatorInterface
    {
        return new HttpRequestValidator($this->getValidateRequestPlugins(), $this->createResourceRouteLoader(), $this->getConfig());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Plugin\Rest\GlueControllerListenerPlugin
     */
    public function createControllerListener(): GlueControllerListenerPlugin
    {
        return new GlueControllerListenerPlugin();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    public function createRestResourceBuilder(): RestResourceBuilderInterface
    {
        return new RestResourceBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface
     */
    public function createRestRequestValidator(): RestRequestValidatorInterface
    {
        return new RestRequestValidator($this->getValidateRestRequestPlugins());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ControllerCallbacksInterface
     */
    public function createControllerCallbacks(): ControllerCallbacksInterface
    {
        return new ControllerCallbacks($this->getControllerBeforeActionPlugins(), $this->getControllerAfterActionPlugins());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface
     */
    public function createVersionResolver(): VersionResolverInterface
    {
        return new VersionResolver($this->createContentTypeResolver());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    public function createContentTypeResolver(): ContentTypeResolverInterface
    {
        return new ContentTypeResolver();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Cors\CorsResponseInterface
     */
    public function createCorsResponse(): CorsResponseInterface
    {
        return new CorsResponse($this->createResourceRouteLoader(), $this->getConfig());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface
     */
    protected function createLanguageNegotiation(): LanguageNegotiationInterface
    {
        return new LanguageNegotiation($this->getStoreClient(), $this->createNegotiator());
    }

    /**
     * @return \Negotiation\LanguageNegotiator
     */
    protected function createNegotiator(): LanguageNegotiator
    {
        return new LanguageNegotiator();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponsePaginationInterface
     */
    protected function createResponsePagination(): ResponsePaginationInterface
    {
        return new ResponsePagination($this->getConfig()->getGlueDomainName());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationshipInterface
     */
    protected function createResponseRelationship(): ResponseRelationshipInterface
    {
        return new ResponseRelationship($this->createResourceRelationshipLoader());
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface[]
     */
    protected function getValidateRestRequestPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_VALIDATE_REST_REQUEST);
    }

    /**
     * @return \Spryker\Glue\Kernel\Application
     */
    protected function getGlueApplication(): Application
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::APPLICATION_GLUE);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): GlueApplicationToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return array
     */
    protected function getResourceRoutePlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_RESOURCE_ROUTES);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected function getResourceProviderPlugins(): ResourceRelationshipCollectionInterface
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_RESOURCE_RELATIONSHIP);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[]
     */
    protected function getValidateRequestPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_VALIDATE_HTTP_REQUEST);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatRequestPluginInterface[]
     */
    protected function getFormatRequestPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_FORMAT_REQUEST);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseDataPluginInterface[]
     */
    protected function getFormatResponseDataPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_FORMAT_RESPONSE_DATA);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface[]
     */
    protected function getFormatResponseHeadersPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_FORMAT_RESPONSE_HEADERS);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface
     */
    protected function getStoreClient(): GlueApplicationToStoreClientInterface
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface[]
     */
    protected function getControllerBeforeActionPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_CONTROLLER_BEFORE_ACTION);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerAfterActionPluginInterface[]
     */
    protected function getControllerAfterActionPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_CONTROLLER_AFTER_ACTION);
    }
}
