<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest\JsonApi;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\Cors\CorsResponse;
use Spryker\Glue\GlueApplication\Rest\Cors\CorsResponseInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoader;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;
use Spryker\Glue\GlueApplication\Rest\Uri\UriParser;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestRequest;
use SprykerTest\Glue\GlueApplication\Stub\RestResponse;
use SprykerTest\Glue\GlueApplication\Stub\TestResourceRoutePlugin;
use SprykerTest\Glue\GlueApplication\Stub\TestResourceWithParentRoutePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group JsonApi
 * @group CorsResponseTest
 * Add your own group annotations below this line
 */
class CorsResponseTest extends Unit
{
    /**
     * @var string
     */
    protected const RESOURCE_TYPE = 'bar';

    /**
     * @var int
     */
    protected const RESOURCE_ID = 1;

    /**
     * @var string
     */
    protected const PARENT_RESOURCE_TYPE = 'foo';

    /**
     * @var int
     */
    protected const PARENT_RESOURCE_ID = 1;

    /**
     * @var string
     */
    protected const RESOURCE_TYPE_FAKE = 'fake';

    /**
     * @var string
     */
    protected const REQUEST_METHOD = 'OPTIONS';

    /**
     * @return void
     */
    public function testCorsHeadersShouldReturnAllowMethodsWhenResourceTypeExists(): void
    {
        // Arrange
        $resourceRoutePlugin = $this->createResourceRoutePlugin(static::RESOURCE_TYPE);
        $resourceLoader = $this->createResourceLoader([$resourceRoutePlugin]);
        $corsResponse = $this->createCorsResponse($resourceLoader);

        $restRequest = $this->createRestRequest(static::REQUEST_METHOD, static::RESOURCE_TYPE, $this->getUri());
        $restResponse = $this->createRestResponse();

        // Act
        $restResponse = $corsResponse->addCorsHeaders($restRequest, $restResponse);

        // Assert
        $this->assertNotEmpty($restResponse->getHeaders()[RequestConstantsInterface::HEADER_ACCESS_CONTROL_ALLOW_METHODS]);
    }

    /**
     * @return void
     */
    public function testCorsHeadersShouldNotReturnAllowMethodsWhenResourceTypeDoesNotExist(): void
    {
        // Arrange
        $resourceRoutePlugin = $this->createResourceRoutePlugin(static::RESOURCE_TYPE_FAKE);
        $resourceLoader = $this->createResourceLoader([$resourceRoutePlugin]);
        $corsResponse = $this->createCorsResponse($resourceLoader);

        $restRequest = $this->createRestRequest(static::REQUEST_METHOD, static::RESOURCE_TYPE, $this->getUri());
        $restResponse = $this->createRestResponse();

        // Act
        $restResponse = $corsResponse->addCorsHeaders($restRequest, $restResponse);

        // Assert
        $this->assertEmpty($restResponse->getHeaders()[RequestConstantsInterface::HEADER_ACCESS_CONTROL_ALLOW_METHODS]);
    }

    /**
     * @return void
     */
    public function testCorsHeadersShouldReturnAllowMethodsWhenParentResourceTypeExists(): void
    {
        // Arrange
        $resourceWithParentRoutePlugin = $this->createResourceWithParentRoutePlugin(static::RESOURCE_TYPE, static::PARENT_RESOURCE_TYPE);
        $resourceLoader = $this->createResourceLoader([$resourceWithParentRoutePlugin]);
        $corsResponse = $this->createCorsResponse($resourceLoader);

        $restRequest = $this->createRestRequest(static::REQUEST_METHOD, static::RESOURCE_TYPE, $this->getUriWithParentResource());
        $restResponse = $this->createRestResponse();

        // Act
        $restResponse = $corsResponse->addCorsHeaders($restRequest, $restResponse);

        // Assert
        $this->assertNotEmpty($restResponse->getHeaders()[RequestConstantsInterface::HEADER_ACCESS_CONTROL_ALLOW_METHODS]);
    }

    /**
     * @return void
     */
    public function testCorsHeadersShouldNotReturnAllowMethodsWhenParentResourceTypeDoesNotExist(): void
    {
        // Arrange
        $resourceWithParentRoutePlugin = $this->createResourceWithParentRoutePlugin(static::RESOURCE_TYPE_FAKE, static::PARENT_RESOURCE_TYPE);
        $resourceLoader = $this->createResourceLoader([$resourceWithParentRoutePlugin]);
        $corsResponse = $this->createCorsResponse($resourceLoader);

        $restRequest = $this->createRestRequest(static::REQUEST_METHOD, static::RESOURCE_TYPE, $this->getUriWithParentResource());
        $restResponse = $this->createRestResponse();

        // Act
        $restResponse = $corsResponse->addCorsHeaders($restRequest, $restResponse);

        // Assert
        $this->assertEmpty($restResponse->getHeaders()[RequestConstantsInterface::HEADER_ACCESS_CONTROL_ALLOW_METHODS]);
    }

    /**
     * @return void
     */
    public function testCorsHeadersShouldReturnAllowMethodsWhenParentResourceTypeAndTheSameResourceTypeNameExist(): void
    {
        // Arrange
        $resourceWithParentRoutePlugin1 = $this->createResourceWithParentRoutePlugin(static::RESOURCE_TYPE, static::PARENT_RESOURCE_TYPE);
        $resourceWithParentRoutePlugin2 = $this->createResourceWithParentRoutePlugin(static::RESOURCE_TYPE, static::PARENT_RESOURCE_TYPE . '_second');
        $resourceLoader = $this->createResourceLoader([$resourceWithParentRoutePlugin1, $resourceWithParentRoutePlugin2]);
        $corsResponse = $this->createCorsResponse($resourceLoader);

        $restRequest = $this->createRestRequest(static::REQUEST_METHOD, static::RESOURCE_TYPE, $this->getUriWithParentResource());
        $restResponse = $this->createRestResponse();

        // Act
        $restResponse = $corsResponse->addCorsHeaders($restRequest, $restResponse);

        // Assert
        $this->assertNotEmpty($restResponse->getHeaders()[RequestConstantsInterface::HEADER_ACCESS_CONTROL_ALLOW_METHODS]);
    }

    /**
     * @param string $resourceType
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface
     */
    protected function createResourceRoutePlugin(string $resourceType): ResourceRoutePluginInterface
    {
        return new TestResourceRoutePlugin($resourceType);
    }

    /**
     * @param string $resourceType
     * @param string $parentResourceType
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface
     */
    protected function createResourceWithParentRoutePlugin(string $resourceType, string $parentResourceType): ResourceRoutePluginInterface
    {
        return new TestResourceWithParentRoutePlugin($resourceType, $parentResourceType);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface $resourceRouteLoader
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Cors\CorsResponseInterface
     */
    protected function createCorsResponse(ResourceRouteLoaderInterface $resourceRouteLoader): CorsResponseInterface
    {
        $glueApplicationConfig = new GlueApplicationConfig();

        $uriParser = new UriParser();

        return new CorsResponse($resourceRouteLoader, $glueApplicationConfig, $uriParser);
    }

    /**
     * @param string $method
     * @param string $resourceType
     * @param string $uri
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected function createRestRequest(string $method, string $resourceType, string $uri): RestRequestInterface
    {
        return (new RestRequest())->createRestRequest($method, $resourceType, $uri);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestResponse(): RestResponseInterface
    {
        return (new RestResponse())->createRestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[] $resourceRoutePlugins
     *
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface
     */
    protected function createResourceLoader(array $resourceRoutePlugins): ResourceRouteLoaderInterface
    {
        $versionResolverMock = $this->getMockBuilder(VersionResolverInterface::class)
            ->getMock();

        return new ResourceRouteLoader($resourceRoutePlugins, $versionResolverMock, []);
    }

    /**
     * @return string
     */
    protected function getUri(): string
    {
        return sprintf('/%s/%s', static::RESOURCE_TYPE, static::RESOURCE_ID);
    }

    /**
     * @return string
     */
    protected function getUriWithParentResource(): string
    {
        return sprintf(
            '/%s/%s/%s/%s',
            static::PARENT_RESOURCE_TYPE,
            static::PARENT_RESOURCE_ID,
            static::RESOURCE_TYPE,
            static::RESOURCE_ID,
        );
    }
}
