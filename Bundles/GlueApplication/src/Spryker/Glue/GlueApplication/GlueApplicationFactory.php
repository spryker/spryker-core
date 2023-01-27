<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication;

use Negotiation\LanguageNegotiator;
use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationBootstrapResolver;
use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationBootstrapResolverInterface;
use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationProxy;
use Spryker\Glue\GlueApplication\ApiApplication\GlueStorefrontFallbackApiApplication;
use Spryker\Glue\GlueApplication\ApiApplication\RequestFlowExecutor;
use Spryker\Glue\GlueApplication\ApiApplication\RequestFlowExecutorInterface;
use Spryker\Glue\GlueApplication\Builder\Request\AttributesRequestBuilder;
use Spryker\Glue\GlueApplication\Builder\Request\FilterFieldRequestBuilder;
use Spryker\Glue\GlueApplication\Builder\Request\PaginationParameterRequestBuilder;
use Spryker\Glue\GlueApplication\Builder\Request\RequestBuilderInterface;
use Spryker\Glue\GlueApplication\Builder\Request\SortParameterRequestBuilder;
use Spryker\Glue\GlueApplication\Builder\Request\SparseFieldRequestBuilder;
use Spryker\Glue\GlueApplication\Builder\RequestBuilder as RequestBuilderWrapper;
use Spryker\Glue\GlueApplication\Builder\RequestBuilderInterface as RequestBuilderWrapperInterface;
use Spryker\Glue\GlueApplication\Cache\Reader\ControllerCacheReader;
use Spryker\Glue\GlueApplication\Cache\Reader\ControllerCacheReaderInterface;
use Spryker\Glue\GlueApplication\Cache\Writer\ControllerCacheWriter;
use Spryker\Glue\GlueApplication\Cache\Writer\ControllerCacheWriterInterface;
use Spryker\Glue\GlueApplication\ContentNegotiator\ContentNegotiator;
use Spryker\Glue\GlueApplication\ContentNegotiator\ContentNegotiatorInterface;
use Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface;
use Spryker\Glue\GlueApplication\Dependency\External\GlueApplicationToInflectorInterface;
use Spryker\Glue\GlueApplication\Dependency\External\GlueApplicationToSymfonyFilesystemInterface;
use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface;
use Spryker\Glue\GlueApplication\Descriptor\TextDescriptor;
use Spryker\Glue\GlueApplication\Encoder\Response\JsonResponseEncoderStrategy;
use Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface;
use Spryker\Glue\GlueApplication\Executor\ResourceExecutor;
use Spryker\Glue\GlueApplication\Executor\ResourceExecutorInterface;
use Spryker\Glue\GlueApplication\Formatter\Response\ResponseFormatter as DefaultConventionResponseFormatter;
use Spryker\Glue\GlueApplication\Formatter\Response\ResponseFormatterInterface as DefaultConventionResponseFormatterInterface;
use Spryker\Glue\GlueApplication\Formatter\ResponseFormatter;
use Spryker\Glue\GlueApplication\Formatter\ResponseFormatterInterface;
use Spryker\Glue\GlueApplication\Formatter\Schema\RestApiSchemaFormatter;
use Spryker\Glue\GlueApplication\Formatter\Schema\RestApiSchemaParametersFormatter;
use Spryker\Glue\GlueApplication\Formatter\Schema\RestApiSchemaParametersFormatterInterface;
use Spryker\Glue\GlueApplication\Formatter\Schema\SchemaFormatterInterface;
use Spryker\Glue\GlueApplication\Http\Context\ContextHttpExpander;
use Spryker\Glue\GlueApplication\Http\Context\ContextHttpExpanderInterface;
use Spryker\Glue\GlueApplication\Http\Request\RequestBuilder as HttpRequestBuilder;
use Spryker\Glue\GlueApplication\Http\Request\RequestBuilderInterface as HttpRequestBuilderInterface;
use Spryker\Glue\GlueApplication\Http\Response\HttpSender;
use Spryker\Glue\GlueApplication\Http\Response\HttpSenderInterface;
use Spryker\Glue\GlueApplication\Plugin\Console\Helper\DescriptorHelper;
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
use Spryker\Glue\GlueApplication\Rest\Request\CorsHttpRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\CorsHttpRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\Request\FormattedControllerBeforeAction;
use Spryker\Glue\GlueApplication\Rest\Request\FormattedControllerBeforeActionInterface;
use Spryker\Glue\GlueApplication\Rest\Request\HeadersHttpRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\HeadersHttpRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\Request\PaginationParametersHttpRequestValidator;
use Spryker\Glue\GlueApplication\Rest\Request\PaginationParametersHttpRequestValidatorInterface;
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
use Spryker\Glue\GlueApplication\Rest\ResourceRouter as RestResourceRouter;
use Spryker\Glue\GlueApplication\Rest\ResourceRouterInterface as RestResourceRouterInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilder;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatter as RestResponseFormatter;
use Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatterInterface as RestResponseFormatterInterface;
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
use Spryker\Glue\GlueApplication\Rest\Uri\UriParser as RestUriParser;
use Spryker\Glue\GlueApplication\Rest\Uri\UriParserInterface as RestUriParserInterface;
use Spryker\Glue\GlueApplication\Rest\User\RestUserValidator;
use Spryker\Glue\GlueApplication\Rest\User\RestUserValidatorInterface;
use Spryker\Glue\GlueApplication\Rest\User\UserProvider;
use Spryker\Glue\GlueApplication\Rest\User\UserProviderInterface;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolver;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Builder\RouterBuilder;
use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Builder\RouterBuilderInterface;
use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Cache\RouterCacheCollector;
use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Cache\RouterCacheCollectorInterface;
use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\CustomRouteMatcher;
use Spryker\Glue\GlueApplication\Router\ResourceRouter\ConventionResourceFilter;
use Spryker\Glue\GlueApplication\Router\ResourceRouter\ConventionResourceFilterInterface;
use Spryker\Glue\GlueApplication\Router\ResourceRouter\RequestResourcePluginFilter;
use Spryker\Glue\GlueApplication\Router\ResourceRouter\RequestResourcePluginFilterInterface;
use Spryker\Glue\GlueApplication\Router\ResourceRouter\ResourceRouteMatcher;
use Spryker\Glue\GlueApplication\Router\ResourceRouter\Uri\UriParser;
use Spryker\Glue\GlueApplication\Router\ResourceRouter\Uri\UriParserInterface;
use Spryker\Glue\GlueApplication\Router\RouteMatcherCollection;
use Spryker\Glue\GlueApplication\Router\RouteMatcherInterface;
use Spryker\Glue\GlueApplication\Serialize\Decoder\DecoderInterface;
use Spryker\Glue\GlueApplication\Serialize\Decoder\JsonDecoder;
use Spryker\Glue\GlueApplication\Serialize\Encoder\EncoderInterface;
use Spryker\Glue\GlueApplication\Serialize\Encoder\JsonEncoder;
use Spryker\Glue\GlueApplication\Validator\Request\AcceptedFormatValidator;
use Spryker\Glue\GlueApplication\Validator\Request\RequestValidatorInterface as RequestRequestValidatorInterface;
use Spryker\Glue\GlueApplication\Validator\RequestValidator;
use Spryker\Glue\GlueApplication\Validator\RequestValidatorInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\ApplicationInterface;
use Spryker\Shared\Kernel\Container\ContainerProxy;
use Spryker\Shared\Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationConfig getConfig()
 */
class GlueApplicationFactory extends AbstractFactory
{
    /**
     * @deprecated Will be removed without replacement.
     *
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
            $this->createRestUserValidator(),
            $this->createRestResourceBuilder(),
            $this->createRestControllerCallbacks(),
            $this->getConfig(),
            $this->createUserProvider(),
            $this->createFormattedControllerBeforeAction(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestFormatterInterface
     */
    public function createRestRequestFormatter(): RequestFormatterInterface
    {
        return new RequestFormatter(
            $this->createRestRequestMetaDataExtractor(),
            $this->createRestRequestResourceExtractor(),
            $this->getConfig(),
            $this->getFormatRequestPlugins(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseFormatterInterface
     */
    public function createRestResponseFormatter(): RestResponseFormatterInterface
    {
        return new RestResponseFormatter(
            $this->createRestEncoderMatcher(),
            $this->createRestResponseBuilder(),
            $this->getFormatResponseDataPlugins(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface
     */
    public function createRestResourceRelationshipLoader(): ResourceRelationshipLoaderInterface
    {
        return new ResourceRelationshipLoader($this->getResourceProviderPlugins());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouterInterface
     */
    public function createRestResourceRouter(): RestResourceRouterInterface
    {
        return new RestResourceRouter(
            $this->createRestHttpRequestValidator(),
            $this->getGlueApplication(),
            $this->createRestUriParser(),
            $this->createRestResourceRouteLoader(),
            $this->getRouterParameterExpanderPlugins(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseBuilderInterface
     */
    public function createRestResponseBuilder(): ResponseBuilderInterface
    {
        return new ResponseBuilder(
            $this->getConfig()->getGlueDomainName(),
            $this->createRestResponsePagination(),
            $this->createRestResponseRelationship(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseHeadersInterface
     */
    public function createRestResponseHeaders(): ResponseHeadersInterface
    {
        return new ResponseHeaders(
            $this->getFormatResponseHeadersPlugins(),
            $this->createRestContentTypeResolver(),
            $this->getConfig(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestMetaDataExtractorInterface
     */
    public function createRestRequestMetaDataExtractor(): RequestMetaDataExtractorInterface
    {
        return new RequestMetaDataExtractor(
            $this->createRestVersionResolver(),
            $this->createRestContentTypeResolver(),
            $this->createLanguageNegotiation(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Serialize\DecoderMatcherInterface
     */
    public function createRestDecoderMatcher(): DecoderMatcherInterface
    {
        return new DecoderMatcher([
            DecoderMatcher::DEFAULT_FORMAT => $this->createJsonDecoder(),
        ]);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Serialize\EncoderMatcherInterface
     */
    public function createRestEncoderMatcher(): EncoderMatcherInterface
    {
        return new EncoderMatcher([
            EncoderMatcher::DEFAULT_FORMAT => $this->createJsonEncoder(),
        ]);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Serialize\Encoder\EncoderInterface
     */
    public function createJsonEncoder(): EncoderInterface
    {
        return new JsonEncoder($this->getUtilEncodingService());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Serialize\Decoder\DecoderInterface
     */
    public function createJsonDecoder(): DecoderInterface
    {
        return new JsonDecoder($this->getUtilEncodingService());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Uri\UriParserInterface
     */
    public function createRestUriParser(): RestUriParserInterface
    {
        return new RestUriParser($this->createRestVersionResolver());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface
     */
    public function createRestResourceRouteLoader(): ResourceRouteLoaderInterface
    {
        return new ResourceRouteLoader(
            $this->getResourceRoutePlugins(),
            $this->createRestVersionResolver(),
            $this->getRouterParameterExpanderPlugins(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\HttpRequestValidatorInterface
     */
    public function createRestHttpRequestValidator(): HttpRequestValidatorInterface
    {
        return new HttpRequestValidator(
            $this->getValidateRequestPlugins(),
            $this->createRestResourceRouteLoader(),
            $this->getConfig(),
            $this->createHeadersHttpRequestValidator(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\FormattedControllerBeforeActionInterface
     */
    public function createFormattedControllerBeforeAction(): FormattedControllerBeforeActionInterface
    {
        return new FormattedControllerBeforeAction($this->getFormattedControllerBeforeActionPlugins());
    }

    /**
     * @deprecated Use {@link \Spryker\Glue\GlueApplication\Plugin\EventDispatcher\GlueRestControllerListenerEventDispatcherPlugin} instead.
     *
     * @return \Spryker\Glue\GlueApplication\Plugin\Rest\GlueControllerListenerPlugin
     */
    public function createRestControllerListener(): GlueControllerListenerPlugin
    {
        return new GlueControllerListenerPlugin();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    public function createRestResourceBuilder(): RestResourceBuilderInterface
    {
        return new RestResourceBuilder();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RestRequestValidatorInterface
     */
    public function createRestRequestValidator(): RestRequestValidatorInterface
    {
        return new RestRequestValidator($this->getValidateRestRequestPlugins(), $this->getRestRequestValidatorPlugins());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\ControllerCallbacksInterface
     */
    public function createRestControllerCallbacks(): ControllerCallbacksInterface
    {
        return new ControllerCallbacks($this->getControllerBeforeActionPlugins(), $this->getControllerAfterActionPlugins());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface
     */
    public function createRestVersionResolver(): VersionResolverInterface
    {
        return new VersionResolver($this->createRestContentTypeResolver(), $this->getConfig());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\ContentType\ContentTypeResolverInterface
     */
    public function createRestContentTypeResolver(): ContentTypeResolverInterface
    {
        return new ContentTypeResolver();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Cors\CorsResponseInterface
     */
    public function createRestCorsResponse(): CorsResponseInterface
    {
        return new CorsResponse($this->createRestResourceRouteLoader(), $this->getConfig(), $this->createRestUriParser());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Language\LanguageNegotiationInterface
     */
    public function createLanguageNegotiation(): LanguageNegotiationInterface
    {
        return new LanguageNegotiation($this->getStoreClient(), $this->createNegotiator());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Negotiation\LanguageNegotiator
     */
    public function createNegotiator(): LanguageNegotiator
    {
        return new LanguageNegotiator();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponsePaginationInterface
     */
    public function createRestResponsePagination(): ResponsePaginationInterface
    {
        return new ResponsePagination($this->getConfig());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationshipInterface
     */
    public function createRestResponseRelationship(): ResponseRelationshipInterface
    {
        return new ResponseRelationship($this->createRestResourceRelationshipLoader());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestResourceExtractorInterface
     */
    public function createRestRequestResourceExtractor(): RequestResourceExtractorInterface
    {
        return new RequestResourceExtractor(
            $this->createRestResourceBuilder(),
            $this->createRestDecoderMatcher(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\User\UserProviderInterface
     */
    public function createUserProvider(): UserProviderInterface
    {
        return new UserProvider(
            $this->getRestUserFinderPlugins(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\User\RestUserValidatorInterface
     */
    public function createRestUserValidator(): RestUserValidatorInterface
    {
        return new RestUserValidator(
            $this->getRestUserValidatorPlugins(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\PaginationParametersHttpRequestValidatorInterface
     */
    public function createPaginationParametersRequestValidator(): PaginationParametersHttpRequestValidatorInterface
    {
        return new PaginationParametersHttpRequestValidator();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\HeadersHttpRequestValidatorInterface
     */
    public function createHeadersHttpRequestValidator(): HeadersHttpRequestValidatorInterface
    {
        return new HeadersHttpRequestValidator(
            $this->getConfig(),
            $this->createRestResourceRouteLoader(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\CorsHttpRequestValidatorInterface
     */
    public function createCorsHttpRequestValidator(): CorsHttpRequestValidatorInterface
    {
        return new CorsHttpRequestValidator();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface>
     */
    public function getValidateRestRequestPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_VALIDATE_REST_REQUEST);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface>
     */
    public function getRestUserValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_VALIDATE_REST_USER);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestRequestValidatorPluginInterface>
     */
    public function getRestRequestValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_REST_REQUEST_VALIDATOR);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function getGlueApplication(): ContainerInterface
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
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface>
     */
    public function getResourceRoutePlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_RESOURCE_ROUTES);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    public function getResourceProviderPlugins(): ResourceRelationshipCollectionInterface
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_RESOURCE_RELATIONSHIP);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface>
     */
    public function getValidateRequestPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_VALIDATE_HTTP_REQUEST);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormattedControllerBeforeActionPluginInterface>
     */
    public function getFormattedControllerBeforeActionPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_FORMATTED_CONTROLLER_BEFORE_ACTION);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatRequestPluginInterface>
     */
    public function getFormatRequestPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_FORMAT_REQUEST);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseDataPluginInterface>
     */
    public function getFormatResponseDataPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_FORMAT_RESPONSE_DATA);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface>
     */
    public function getFormatResponseHeadersPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_FORMAT_RESPONSE_HEADERS);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientInterface
     */
    public function getStoreClient(): GlueApplicationToStoreClientInterface
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::CLIENT_STORE);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface>
     */
    public function getControllerBeforeActionPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_CONTROLLER_BEFORE_ACTION);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerAfterActionPluginInterface>
     */
    public function getControllerAfterActionPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_CONTROLLER_AFTER_ACTION);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserFinderPluginInterface>
     */
    public function getRestUserFinderPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_REST_USER_FINDER);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function createServiceContainer(): ContainerInterface
    {
        return new ContainerProxy(['logger' => null, 'debug' => $this->getConfig()->isDebugModeEnabled(), 'charset' => 'UTF-8']);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface>
     */
    public function getApplicationPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_APPLICATION);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouterParameterExpanderPluginInterface>
     */
    public function getRouterParameterExpanderPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_ROUTER_PARAMETER_EXPANDER);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueContextExpanderPluginInterface>
     */
    public function getGlueContextExpanderPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGIN_API_CONTEXT_EXPANDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface>
     */
    public function getBootstrapPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_GLUE_APPLICATION_BOOTSTRAP);
    }

    /**
     * @param array<string> $glueApplicationBootstrapPluginClassNames
     *
     * @return \Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationBootstrapResolverInterface
     */
    public function createApiApplicationBootstrapResolver(array $glueApplicationBootstrapPluginClassNames = []): ApiApplicationBootstrapResolverInterface
    {
        return new ApiApplicationBootstrapResolver($glueApplicationBootstrapPluginClassNames, $this->getBootstrapPlugins());
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin
     *
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function createApiApplicationProxy(GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin): ApplicationInterface
    {
        return new ApiApplicationProxy(
            $glueApplicationBootstrapPlugin,
            $this->createRequestFlowExecutor(),
            $this->getCommunicationProtocolPlugins(),
            $this->getConventionPlugins(),
            $this->createHttpRequestBuilder(),
            $this->createHttpSender(),
            $this->createContentNegotiator(),
        );
    }

    /**
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function createGlueStorefrontFallbackApiApplication(): ApplicationInterface
    {
        return new GlueStorefrontFallbackApiApplication($this->createServiceContainer(), $this->getApplicationPlugins());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\ApiApplication\RequestFlowExecutorInterface
     */
    public function createRequestFlowExecutor(): RequestFlowExecutorInterface
    {
        return new RequestFlowExecutor(
            $this->createResourceExecutor(),
            $this->createRouteMatcher(),
            $this->createRequestBuilder(),
            $this->createRequestValidator(),
            $this->createResponseFormatter(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Builder\RequestBuilderInterface
     */
    public function createRequestBuilder(): RequestBuilderWrapperInterface
    {
        return new RequestBuilderWrapper(
            $this->getRequestBuilderPlugins(),
            $this->createRequestBuilders(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Validator\RequestValidatorInterface
     */
    public function createRequestValidator(): RequestValidatorInterface
    {
        return new RequestValidator(
            $this->getRequestValidatorPlugins(),
            $this->getRequestAfterRoutingValidatorPlugins(),
            $this->createRequestValidators(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Formatter\ResponseFormatterInterface
     */
    public function createResponseFormatter(): ResponseFormatterInterface
    {
        return new ResponseFormatter(
            $this->getResponseFormatterPlugins(),
            $this->createResponseFormatters(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface>
     */
    public function getCommunicationProtocolPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_COMMUNICATION_PROTOCOL);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface>
     */
    public function getConventionPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_CONVENTION);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Executor\ResourceExecutorInterface
     */
    public function createResourceExecutor(): ResourceExecutorInterface
    {
        return new ResourceExecutor(
            $this->createControllerCacheReader(),
            $this->createControllerCacheWriter(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Router\ResourceRouter\RequestResourcePluginFilterInterface
     */
    public function createRequestResourcePluginFilter(): RequestResourcePluginFilterInterface
    {
        return new RequestResourcePluginFilter($this->createConventionResourceFilter());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Router\ResourceRouter\ConventionResourceFilterInterface
     */
    public function createConventionResourceFilter(): ConventionResourceFilterInterface
    {
        return new ConventionResourceFilter($this->getConventionPlugins());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Plugin\Console\Helper\DescriptorHelper
     */
    public function createDescriptorHelper(): DescriptorHelper
    {
        return new DescriptorHelper(
            $this->createTextDescriptor(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Descriptor\TextDescriptor
     */
    public function createTextDescriptor(): TextDescriptor
    {
        return new TextDescriptor(
            $this->getTableColumnExpanderPlugins(),
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Symfony\Component\Console\Style\SymfonyStyle
     */
    public function createConsoleOutputStyle(InputInterface $input, OutputInterface $output): SymfonyStyle
    {
        return new SymfonyStyle($input, $output);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Router\ResourceRouter\Uri\UriParserInterface
     */
    public function createUriParser(): UriParserInterface
    {
        return new UriParser();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Cache\Writer\ControllerCacheWriterInterface
     */
    public function createControllerCacheWriter(): ControllerCacheWriterInterface
    {
        return new ControllerCacheWriter(
            $this->getControllerCacheCollectorPlugins(),
            $this->getConfig(),
            $this->getFilesystem(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Cache\Reader\ControllerCacheReaderInterface
     */
    public function createControllerCacheReader(): ControllerCacheReaderInterface
    {
        return new ControllerCacheReader(
            $this->createControllerCacheWriter(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Dependency\External\GlueApplicationToSymfonyFilesystemInterface
     */
    public function getFilesystem(): GlueApplicationToSymfonyFilesystemInterface
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::FILESYSTEM);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerCacheCollectorPluginInterface>
     */
    public function getControllerCacheCollectorPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_CONTROLLER_CACHE_COLLECTOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiApplicationEndpointProviderPluginInterface>
     */
    public function getGlueApplicationRouterProviderPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_GLUE_APPLICATION_ROUTER_PROVIDER);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Router\RouteMatcherInterface
     */
    public function createCustomRouteMatcher(): RouteMatcherInterface
    {
        return new CustomRouteMatcher($this->createRouterBuilder());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Router\RouteMatcherInterface
     */
    public function createResourceRouteMatcher(): RouteMatcherInterface
    {
        return new ResourceRouteMatcher(
            $this->getResourcesProviderPlugins(),
            $this->createUriParser(),
            $this->createRequestResourcePluginFilter(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Router\RouteMatcherInterface
     */
    public function createRouteMatcher(): RouteMatcherInterface
    {
        return new RouteMatcherCollection(
            $this->getRouteMatchers(),
            $this->getConfig(),
            $this->createRouterCacheCollector(),
        );
    }

    /**
     * @return array<\Spryker\Glue\GlueApplication\Router\RouteMatcherInterface>
     */
    public function getRouteMatchers(): array
    {
        return [
            'routes' => $this->createCustomRouteMatcher(),
            'resources' => $this->createResourceRouteMatcher(),
        ];
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Builder\RouterBuilderInterface
     */
    public function createRouterBuilder(): RouterBuilderInterface
    {
        return new RouterBuilder($this->getRoutesProviderPlugins());
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RoutesProviderPluginInterface>
     */
    public function getRoutesProviderPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_ROUTES_PROVIDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourcesProviderPluginInterface>
     */
    public function getResourcesProviderPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_RESOURCES_PROVIDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\TableColumnExpanderPluginInterface>
     */
    public function getTableColumnExpanderPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_TABLE_COLUMN_EXPANDER);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Cache\RouterCacheCollectorInterface
     */
    public function createRouterCacheCollector(): RouterCacheCollectorInterface
    {
        return new RouterCacheCollector(
            $this->createRouterBuilder(),
            $this->getRoutesProviderPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    public function getRequestBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_REQUEST_BUILDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function getRequestValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_REQUEST_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    public function getRequestAfterRoutingValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    public function getResponseFormatterPlugins(): array
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::PLUGINS_RESPONSE_FORMATTER);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Dependency\External\GlueApplicationToInflectorInterface
     */
    public function getInflector(): GlueApplicationToInflectorInterface
    {
        return $this->getProvidedDependency(GlueApplicationDependencyProvider::INFLECTOR);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Formatter\Schema\RestApiSchemaParametersFormatterInterface
     */
    public function createRestApiSchemaParametersFormatter(): RestApiSchemaParametersFormatterInterface
    {
        return new RestApiSchemaParametersFormatter();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Validator\Request\RequestValidatorInterface
     */
    public function createAcceptedFormatValidator(): RequestRequestValidatorInterface
    {
        return new AcceptedFormatValidator($this->getResponseEncoderStrategies());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Builder\Request\RequestBuilderInterface
     */
    public function createFilterFieldRequestBuilder(): RequestBuilderInterface
    {
        return new FilterFieldRequestBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Builder\Request\RequestBuilderInterface
     */
    public function createSparseFieldRequestBuilder(): RequestBuilderInterface
    {
        return new SparseFieldRequestBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Builder\Request\RequestBuilderInterface
     */
    public function createAttributesRequestBuilder(): RequestBuilderInterface
    {
        return new AttributesRequestBuilder(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Formatter\Response\ResponseFormatterInterface
     */
    public function createDefaultResponseFormatter(): DefaultConventionResponseFormatterInterface
    {
        return new DefaultConventionResponseFormatter(
            $this->getResponseEncoderStrategies(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Http\Request\RequestBuilderInterface
     */
    public function createHttpRequestBuilder(): HttpRequestBuilderInterface
    {
        return new HttpRequestBuilder(
            $this->createRequest(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Http\Response\HttpSenderInterface;
     */
    public function createHttpSender(): HttpSenderInterface
    {
        return new HttpSender(
            $this->createResponse(),
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createResponse(): Response
    {
        return new Response();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function createRequest(): Request
    {
        return Request::createFromGlobals();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Http\Context\ContextHttpExpanderInterface
     */
    public function createContextHttpExpander(): ContextHttpExpanderInterface
    {
        return new ContextHttpExpander(
            $this->createRequest(),
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Formatter\Schema\SchemaFormatterInterface
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
     * @return \Spryker\Glue\GlueApplication\Builder\Request\RequestBuilderInterface
     */
    public function createPaginationParameterRequestBuilder(): RequestBuilderInterface
    {
        return new PaginationParameterRequestBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Builder\Request\RequestBuilderInterface
     */
    public function createSortParameterRequestBuilder(): RequestBuilderInterface
    {
        return new SortParameterRequestBuilder();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface
     */
    public function createJsonResponseEncoderStrategy(): ResponseEncoderStrategyInterface
    {
        return new JsonResponseEncoderStrategy($this->getUtilEncodingService());
    }

    /**
     * @return array<\Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface>
     */
    public function getResponseEncoderStrategies(): array
    {
        return [
            $this->createJsonResponseEncoderStrategy(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplication\Builder\Request\RequestBuilderInterface>
     */
    public function createRequestBuilders(): array
    {
        return [
            $this->createAttributesRequestBuilder(),
            $this->createFilterFieldRequestBuilder(),
            $this->createPaginationParameterRequestBuilder(),
            $this->createSortParameterRequestBuilder(),
            $this->createSparseFieldRequestBuilder(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplication\Validator\Request\RequestValidatorInterface>
     */
    public function createRequestValidators(): array
    {
        return [
            $this->createAcceptedFormatValidator(),
        ];
    }

    /**
     * @return array<\Spryker\Glue\GlueApplication\Formatter\Response\ResponseFormatterInterface>
     */
    public function createResponseFormatters(): array
    {
        return [
            $this->createDefaultResponseFormatter(),
        ];
    }

    /**
     * @return \Spryker\Glue\GlueApplication\ContentNegotiator\ContentNegotiatorInterface
     */
    public function createContentNegotiator(): ContentNegotiatorInterface
    {
        return new ContentNegotiator(
            $this->getConventionPlugins(),
            $this->getResponseEncoderStrategies(),
        );
    }
}
