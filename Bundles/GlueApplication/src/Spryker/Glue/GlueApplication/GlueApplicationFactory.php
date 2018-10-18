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
use Spryker\Glue\GlueApplication\Rest\Request\RequestResourceExtractor;
use Spryker\Glue\GlueApplication\Rest\Request\RequestResourceExtractorInterface;
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
use Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcher;
use Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcher;
use Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface;
use Spryker\Glue\GlueApplication\Rest\Uri\UriParser;
use Spryker\Glue\GlueApplication\Rest\Uri\UriParserInterface;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolver;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Spryker\Glue\GlueApplication\Serialize\Decoder\DecoderInterface;
use Spryker\Glue\GlueApplication\Serialize\Decoder\JsonDecoder;
use Spryker\Glue\GlueApplication\Serialize\Encoder\EncoderInterface;
use Spryker\Glue\GlueApplication\Serialize\Encoder\JsonEncoder;
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
    public function createRestControllerFilter(): ControllerFilterInterface
    {
        return new ControllerFilter(
            $this->createRestRequestFormatter(),
            $this->createRestResponseFormatter(),
            $this->createRestResponseHeaders(),
            $this->createRestHttpRequestValidator(),
            $this->createRestRequestValidator(),
            $this->createRestResourceBuilder(),
            $this->createRestControllerCallbacks(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestFormatterInterface
     */
    public function createRestRequestFormatter(): RequestFormatterInterface
    {
        return new RequestFormatter(
            $this->createRestRequestMetaDataExtractor(),
            $this->createRestRequestResourceExtractor(),
            $this->getFormatRequestPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatterInterface
     */
    public function createRestResponseFormatter(): ResponseFormatterInterface
    {
        return new ResponseFormatter(
            $this->createRestEncoderMatcher(),
            $this->createRestResponseBuilder(),
            $this->getFormatResponseDataPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface
     */
    public function createRestResourceRelationshipLoader(): ResourceRelationshipLoaderInterface
    {
        return new ResourceRelationshipLoader($this->getResourceProviderPlugins());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouterInterface
     */
    public function createRestResourceRouter(): ResourceRouterInterface
    {
        return new ResourceRouter(
            $this->createRestHttpRequestValidator(),
            $this->getGlueApplication(),
            $this->createRestUriParser(),
            $this->createRestResourceRouteLoader()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface
     */
    public function createRestResponseBuilder(): ResponseBuilderInterface
    {
        return new ResponseBuilder(
            $this->getConfig()->getGlueDomainName(),
            $this->createRestResponsePagination(),
            $this->createRestResponseRelationship()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseHeadersInterface
     */
    public function createRestResponseHeaders(): ResponseHeadersInterface
    {
        return new ResponseHeaders(
            $this->getFormatResponseHeadersPlugins(),
            $this->createRestContentTypeResolver(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface
     */
    public function createRestRequestMetaDataExtractor(): RequestMetaDataExtractorInterface
    {
        return new RequestMetaDataExtractor(
            $this->createRestVersionResolver(),
            $this->createRestContentTypeResolver(),
            $this->createLanguageNegotiation()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface
     */
    public function createRestDecoderMatcher(): DecoderMatcherInterface
    {
        return new DecoderMatcher([
            DecoderMatcher::DEFAULT_FORMAT => $this->createJsonDecoder(),
        ]);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface
     */
    public function createRestEncoderMatcher(): EncoderMatcherInterface
    {
        return new EncoderMatcher([
            EncoderMatcher::DEFAULT_FORMAT => $this->createJsonEncoder(),
        ]);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Serialize\Encoder\EncoderInterface
     */
    public function createJsonEncoder(): EncoderInterface
    {
        return new JsonEncoder($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Serialize\Decoder\DecoderInterface
     */
    public function createJsonDecoder(): DecoderInterface
    {
        return new JsonDecoder($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Uri\UriParserInterface
     */
    public function createRestUriParser(): UriParserInterface
    {
        return new UriParser();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface
     */
    public function createRestResourceRouteLoader(): ResourceRouteLoaderInterface
    {
        return new ResourceRouteLoader($this->getResourceRoutePlugins(), $this->createRestVersionResolver());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface
     */
    public function createRestHttpRequestValidator(): HttpRequestValidatorInterface
    {
        return new HttpRequestValidator($this->getValidateRequestPlugins(), $this->createRestResourceRouteLoader(), $this->getConfig());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Plugin\Rest\GlueControllerListenerPlugin
     */
    public function createRestControllerListener(): GlueControllerListenerPlugin
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
    public function createRestControllerCallbacks(): ControllerCallbacksInterface
    {
        return new ControllerCallbacks($this->getControllerBeforeActionPlugins(), $this->getControllerAfterActionPlugins());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface
     */
    public function createRestVersionResolver(): VersionResolverInterface
    {
        return new VersionResolver($this->createRestContentTypeResolver());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    public function createRestContentTypeResolver(): ContentTypeResolverInterface
    {
        return new ContentTypeResolver();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Cors\CorsResponseInterface
     */
    public function createRestCorsResponse(): CorsResponseInterface
    {
        return new CorsResponse($this->createRestResourceRouteLoader(), $this->getConfig());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface
     */
    public function createLanguageNegotiation(): LanguageNegotiationInterface
    {
        return new LanguageNegotiation($this->getStoreClient(), $this->createNegotiator());
    }

    /**
     * @return \Negotiation\LanguageNegotiator
     */
    public function createNegotiator(): LanguageNegotiator
    {
        return new LanguageNegotiator();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponsePaginationInterface
     */
    public function createRestResponsePagination(): ResponsePaginationInterface
    {
        return new ResponsePagination($this->getConfig()->getGlueDomainName());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationshipInterface
     */
    public function createRestResponseRelationship(): ResponseRelationshipInterface
    {
        return new ResponseRelationship($this->createRestResourceRelationshipLoader());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestResourceExtractorInterface
     */
    public function createRestRequestResourceExtractor(): RequestResourceExtractorInterface
    {
        return new RequestResourceExtractor(
            $this->createRestResourceBuilder(),
            $this->createRestDecoderMatcher()
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface[]
     */
    public function getValidateRestRequestPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_VALIDATE_REST_REQUEST);
    }

    /**
     * @return \Spryker\Glue\Kernel\Application
     */
    public function getGlueApplication(): Application
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::APPLICATION_GLUE);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): GlueApplicationToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return array
     */
    public function getResourceRoutePlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_RESOURCE_ROUTES);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    public function getResourceProviderPlugins(): ResourceRelationshipCollectionInterface
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_RESOURCE_RELATIONSHIP);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[]
     */
    public function getValidateRequestPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_VALIDATE_HTTP_REQUEST);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatRequestPluginInterface[]
     */
    public function getFormatRequestPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_FORMAT_REQUEST);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseDataPluginInterface[]
     */
    public function getFormatResponseDataPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_FORMAT_RESPONSE_DATA);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface[]
     */
    public function getFormatResponseHeadersPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_FORMAT_RESPONSE_HEADERS);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface
     */
    public function getStoreClient(): GlueApplicationToStoreClientInterface
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface[]
     */
    public function getControllerBeforeActionPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_CONTROLLER_BEFORE_ACTION);
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerAfterActionPluginInterface[]
     */
    public function getControllerAfterActionPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_CONTROLLER_AFTER_ACTION);
    }
}
