<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Codeception\Util\HttpCode;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use Spryker\Glue\GlueApplication\Bootstrap\GlueBootstrap;
use Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider;
use Spryker\Glue\GlueApplication\GlueApplicationFactory;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationDependencyProvider;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ApplicationIdentifierRequestBuilderPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\BackendApiGlueApplicationBootstrapPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ControllerCacheCollectorPlugin as BackendControllerCacheCollectorPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\LocaleRequestBuilderPlugin;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication\ResourcesProviderPlugin as BackendResourcesProviderPlugin;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueApplication\JsonApiConventionPlugin;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface;
use Spryker\Shared\Application\ApplicationInterface;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use SprykerTest\Glue\Testify\Helper\Stub\HttpSenderStub;
use SprykerTest\Glue\Testify\Helper\Stub\RequestBuilderStub;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GlueBackendApiHelper extends Module implements LastConnectionProviderInterface
{
    use ConfigHelperTrait;
    use DependencyProviderHelperTrait;

    /**
     * @var array<string, string>
     */
    protected const DEFAULT_HEADERS = [
        'Content-Type' => 'application/vnd.api+json', // Without this header the GlueBackendApi will not handle the request.
        'Accept-Language' => 'en',
        'Store' => 'DE',
    ];

    /**
     * @var \SprykerTest\Glue\Testify\Helper\Stub\RequestBuilderStub|null
     */
    protected ?RequestBuilderStub $requestBuilder = null;

    /**
     * @var \SprykerTest\Glue\Testify\Helper\Stub\HttpSenderStub|null
     */
    protected ?HttpSenderStub $httpSender = null;

    /**
     * @var \SprykerTest\Glue\Testify\Helper\JsonConnection|null
     */
    protected ?JsonConnection $lastConnection = null;

    /**
     * @var array<string, string>
     */
    protected array $headers = self::DEFAULT_HEADERS;

    /**
     * @var array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface>
     */
    protected array $jsonApiResourcePlugins = [];

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->disableWhoopsErrorHandler();
        $this->reset();
    }

    /**
     * The WhoopsErrorHandler converts E_USER_DEPRECATED into exception, we need to disable it for tests.
     *
     * @return void
     */
    protected function disableWhoopsErrorHandler(): void
    {
        $this->getConfigHelper()->setConfig(ErrorHandlerConstants::IS_PRETTY_ERROR_HANDLER_ENABLED, false);
    }

    /**
     * @return void
     */
    protected function reset(): void
    {
        $this->requestBuilder = null;
        $this->httpSender = null;
        $this->lastConnection = null;
        $this->headers = static::DEFAULT_HEADERS;
        $this->jsonApiResourcePlugins = [];
    }

    /**
     * @param \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface $jsonApiResourcePlugin
     *
     * @return void
     */
    public function addJsonApiResourcePlugin(JsonApiResourceInterface $jsonApiResourcePlugin): void
    {
        $this->jsonApiResourcePlugins[] = $jsonApiResourcePlugin;
    }

    /**
     * Add another header to the default defined headers.
     *
     * @param string $headerName
     * @param string|int $headerValue
     *
     * @return void
     */
    public function addHeader(string $headerName, string|int $headerValue): void
    {
        $this->headers[$headerName] = $headerValue;
    }

    /**
     * Use this method to override ALL default and previously set headers.
     *
     * @param array<string, string|int> $headers
     *
     * @return void
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @param string $url
     * @param array<mixed, mixed> $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendGet(string $url, array $parameters = []): Response
    {
        return $this->executeRequest($url, 'GET', $parameters);
    }

    /**
     * @param string $url
     * @param array<mixed, mixed> $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendPost(string $url, array $parameters = []): Response
    {
        return $this->executeRequest($url, 'POST', $parameters);
    }

    /**
     * @param string $url
     * @param array<mixed, mixed> $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendPatch(string $url, array $parameters = []): Response
    {
        return $this->executeRequest($url, 'PATCH', $parameters);
    }

    /**
     * @param string $url
     * @param array<mixed, mixed> $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendDelete(string $url, array $parameters = []): Response
    {
        return $this->executeRequest($url, 'DELETE', $parameters);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array<mixed, mixed> $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function executeRequest(string $url, string $method, array $parameters = []): Response
    {
        $request = Request::create($url, $method, $parameters, [], [], [], $parameters ? json_encode($parameters, JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR) : null);

        $request->headers->add($this->headers);

        // Set the predefined Request so that the GlueBackendApiApplication can pick it up instead of creating an empty Request.
        $this->getRequestBuilderStub()->setRequest($request);

        // Run the mocked GlueBackendApiApplication.
        $this->getGlueBackendApiApplication()->run();

        // Get the response that was created from the GlueBackendApiApplication.
        $response = $this->getHttpSenderStub()->getResponse();

        $this->persistLastConnection($request, $response);

        return $response;
    }

    /**
     * Enables access to the Request object. The original class gets it via dependency injection, and we have no control over it.
     *
     * @return \SprykerTest\Glue\Testify\Helper\Stub\RequestBuilderStub
     */
    protected function getRequestBuilderStub(): RequestBuilderStub
    {
        if (!$this->requestBuilder) {
            $this->requestBuilder = Stub::make(RequestBuilderStub::class);
        }

        return $this->requestBuilder;
    }

    /**
     * Enables access to the Response object. The original class gets it via dependency injection, and we have no control over it.
     *
     * @return \SprykerTest\Glue\Testify\Helper\Stub\HttpSenderStub
     */
    protected function getHttpSenderStub(): HttpSenderStub
    {
        if (!$this->httpSender) {
            $responseStub = Stub::construct(Response::class);

            $this->httpSender = Stub::construct(HttpSenderStub::class, [$responseStub]);
        }

        return $this->httpSender;
    }

    /**
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    protected function getGlueBackendApiApplication(): ApplicationInterface
    {
        /** @var \Spryker\Glue\GlueApplication\GlueApplicationFactory $glueApplicationFactory */
        $glueApplicationFactory = Stub::make(GlueApplicationFactory::class, [
            'createHttpRequestBuilder' => $this->getRequestBuilderStub(),
            'createHttpSender' => $this->getHttpSenderStub(),
            'resolveDependencyProvider' => new GlueApplicationDependencyProvider(),
            'getConfig' => $this->getConfigHelper()->getModuleConfig('GlueApplication'),
        ]);

        $this->getDependencyProviderHelper()->setDependency(GlueBackendApiApplicationDependencyProvider::PLUGINS_REQUEST_BUILDER, [
            new ApplicationIdentifierRequestBuilderPlugin(),
            new LocaleRequestBuilderPlugin(),
        ], GlueBackendApiApplicationFactory::class);

        $this->getDependencyProviderHelper()->setDependency(GlueApplicationDependencyProvider::PLUGINS_RESOURCES_PROVIDER, [
            new BackendResourcesProviderPlugin(),
        ], get_class($glueApplicationFactory)); // Since the class name is used for retrieving the dependency and the factory we use here is a mock, we need to map the mocked dependency to the mocked class.

        $this->getDependencyProviderHelper()->setDependency(GlueApplicationDependencyProvider::PLUGINS_CONVENTION, [
            new JsonApiConventionPlugin(),
        ], get_class($glueApplicationFactory));

        $this->getDependencyProviderHelper()->setDependency(GlueApplicationDependencyProvider::PLUGINS_CONTROLLER_CACHE_COLLECTOR, [
            new BackendControllerCacheCollectorPlugin(),
        ], get_class($glueApplicationFactory));

        $this->getDependencyProviderHelper()->setDependency(
            GlueBackendApiApplicationDependencyProvider::PLUGINS_RESOURCE,
            $this->getJsonApiResourcePlugins(),
            GlueBackendApiApplicationFactory::class,
        );

        $application = new GlueBootstrap();
        $application->setFactory($glueApplicationFactory);

        return $application->boot([BackendApiGlueApplicationBootstrapPlugin::class]);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return array<\Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\JsonApiResourceInterface>
     */
    protected function getJsonApiResourcePlugins(): array
    {
        if (count($this->jsonApiResourcePlugins) === 0) {
            throw new InvalidArgumentException('Could not find a JsonApiResourceInterface that defines the routing for the resource under test. Please add one with the "addJsonApiResourcePlugin" method.');
        }

        return $this->jsonApiResourcePlugins;
    }

    /**
     * @param int $code
     *
     * @return void
     */
    public function seeResponseCodeIs(int $code): void
    {
        $failureMessage = sprintf(
            'Expected HTTP Status Code: %s. Actual Status Code: %s',
            HttpCode::getDescription($code),
            HttpCode::getDescription($this->getResponse()->getStatusCode()),
        );
        $this->assertSame($code, $this->getResponse()->getStatusCode(), $failureMessage);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResponse(): Response
    {
        return $this->getHttpSenderStub()->getResponse();
    }

    /**
     * @return void
     */
    public function seeResponseIsJson(): void
    {
        $responseContent = $this->getResponse()->getContent();
        Assert::assertNotEquals('', $responseContent, 'response is empty');
        $this->decodeAndValidateJson($responseContent);
    }

    /**
     * @param string $jsonString
     * @param string $errorFormat
     *
     * @return void
     */
    protected function decodeAndValidateJson(string $jsonString, string $errorFormat = 'Invalid json: %s. System message: %s.'): void
    {
        json_decode($jsonString);
        $errorCode = json_last_error();
        $errorMessage = json_last_error_msg();
        Assert::assertSame(
            JSON_ERROR_NONE,
            $errorCode,
            sprintf(
                $errorFormat,
                $jsonString,
                $errorMessage,
            ),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return static
     */
    protected function persistLastConnection(Request $request, Response $response): self
    {
        $jsonResponse = json_decode($response->getContent(), true);

        $this->lastConnection = (new JsonConnection())
            ->setResponseBody($response->getContent())
            ->setResponseJson(is_array($jsonResponse) ? $jsonResponse : null)
            ->setRequestFiles([])
            ->setRequestMethod($request->getMethod())
            ->setRequestParameters($request->attributes->all())
            ->setRequestUrl(strpos($request->getUri(), '://') !== false ? $request->getUri() : $this->config['url'] . $request->getUri())
            ->setResponseCode($response->getStatusCode());

        return $this;
    }

    /**
     * @return \SprykerTest\Glue\Testify\Helper\Connection|null
     */
    public function getLastConnection(): ?Connection
    {
        return $this->lastConnection;
    }
}
