<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\GlueStorefrontApiApplicationAuthorizationConnector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\AuthorizationIdentityTransfer;
use Generated\Shared\Transfer\CustomRoutesContextTransfer;
use Generated\Shared\Transfer\GlueRequestCustomerTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorClient;
use Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group GlueStorefrontApiApplicationAuthorizationConnector
 * @group GlueStorefrontApiApplicationAuthorizationConnectorClientTest
 * Add your own group annotations below this line
 */
class GlueStorefrontApiApplicationAuthorizationConnectorClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const CONFIG_METHOD_NAME = 'getProtectedPaths';

    /**
     * @var string
     */
    protected const METHOD = 'method';

    /**
     * @var string
     */
    protected const PATH = 'path';

    /**
     * @var string
     */
    protected const GLUE_REQUEST_CUSTOMER = 'glueRequestCustomer';

    /**
     * @var string
     */
    protected const IS_REGULAR_EXPRESSION = 'isRegularExpression';

    /**
     * @var string
     */
    protected const METHODS = 'methods';

    /**
     * @return void
     */
    public function testAuthorizeReturnsTrueForNotProtectedEndpoint(): void
    {
        // Arrange
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([]);
        $authorizationRequestTransfer = $this->tester->createAuthorizationRequestTransfer([
            static::METHOD => 'get',
            static::PATH => 'testRoute',
            static::GLUE_REQUEST_CUSTOMER => new GlueRequestCustomerTransfer(),
        ]);

        // Act
        $result = $glueStorefrontApiApplicationAuthorizationConnectorClient->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsFalseForProtectedEndpointWhichDoesNotContainMethodData(): void
    {
        // Arrange
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/test' => [
                static::IS_REGULAR_EXPRESSION => true,
            ],
        ]);
        $authorizationRequestTransfer = $this->tester->createAuthorizationRequestTransfer([
            static::PATH => '/testRoute',
            static::GLUE_REQUEST_CUSTOMER => new GlueRequestCustomerTransfer(),
        ]);

        // Act
        $result = $glueStorefrontApiApplicationAuthorizationConnectorClient->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsFalseForProtectedEndpointWhichDoesNotContainPathData(): void
    {
        // Arrange
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/test' => [
                static::IS_REGULAR_EXPRESSION => true,
            ],
        ]);
        $authorizationRequestTransfer = $this->tester->createAuthorizationRequestTransfer([
            static::METHOD => 'get',
            static::GLUE_REQUEST_CUSTOMER => new GlueRequestCustomerTransfer(),
        ]);

        // Act
        $result = $glueStorefrontApiApplicationAuthorizationConnectorClient->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsFalseForProtectedEndpointWhenMethodIsProtectedByPathDoesNotContainValidToken(): void
    {
        // Arrange
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/testRoute' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);
        $authorizationRequestTransfer = $this->tester->createAuthorizationRequestTransfer([
            static::METHOD => 'post',
            static::PATH => '/testRoute',
        ]);

        // Act
        $result = $glueStorefrontApiApplicationAuthorizationConnectorClient->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsTrueForProtectedEndpointWhenMethodIsProtectedByPath(): void
    {
        // Arrange
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/testRoute' => [
                static::IS_REGULAR_EXPRESSION => false,
                static::METHODS => [
                    'post',
                    'get',
                ],
            ],
        ]);
        $authorizationRequestTransfer = $this->tester->createAuthorizationRequestTransfer([
            static::METHOD => 'post',
            static::PATH => '/testRoute',
        ], (new AuthorizationIdentityTransfer())->setIdentifier('fake identity'));

        // Act
        $result = $glueStorefrontApiApplicationAuthorizationConnectorClient->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsFalseForProtectedEndpointWhenMethodIsProtectedByRegularExpressionAndDoesNotContainValidToken(): void
    {
        // Arrange
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/\/testRoute\/.+/' => [
                static::IS_REGULAR_EXPRESSION => true,
                static::METHODS => [
                    'get',
                ],
            ],
        ]);
        $authorizationRequestTransfer = $this->tester->createAuthorizationRequestTransfer([
            static::METHOD => 'get',
            static::PATH => '/testRoute/testId',
        ]);

        // Act
        $result = $glueStorefrontApiApplicationAuthorizationConnectorClient->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsTrueForProtectedEndpointWhenMethodIsProtectedByRegularExpression(): void
    {
        // Arrange
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/\/testRoute\/.+/' => [
                static::IS_REGULAR_EXPRESSION => true,
                static::METHODS => [
                    'get',
                ],
            ],
        ]);
        $authorizationRequestTransfer = $this->tester->createAuthorizationRequestTransfer([
            static::METHOD => 'get',
            static::PATH => '/testRoute/testId',
        ], (new AuthorizationIdentityTransfer())->setIdentifier('fake identity'));

        // Act
        $result = $glueStorefrontApiApplicationAuthorizationConnectorClient->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsProtectedWhenMethodIsProtectedByTheFullyQualifiedPathName(): void
    {
        // Arrange
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/testRoute' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);
        $routeTransfer = (new RouteTransfer())
            ->setRoute('/testRoute')
            ->setMethod('post');

        // Act
        $result = $glueStorefrontApiApplicationAuthorizationConnectorClient->isProtected($routeTransfer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsProtectedWhenMethodIsNotProtectedByTheFullyQualifiedPathName(): void
    {
        // Arrange
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/testRoute' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);
        $routeTransfer = (new RouteTransfer())
            ->setRoute('/route')
            ->setMethod('post');

        // Act
        $result = $glueStorefrontApiApplicationAuthorizationConnectorClient->isProtected($routeTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testIsProtectedWhenMethodIsNotProtectedByRegularExpression(): void
    {
        // Arrange
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/\/route\/.+/' => [
                static::IS_REGULAR_EXPRESSION => true,
                static::METHODS => [
                    'get',
                ],
            ],
        ]);
        $routeTransfer = (new RouteTransfer())
            ->setRoute('/testRoute/testId')
            ->setMethod('get');

        // Act
        $result = $glueStorefrontApiApplicationAuthorizationConnectorClient->isProtected($routeTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testIsProtectedWhenMethodIsProtectedByRegularExpression(): void
    {
        // Arrange
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/\/testRoute\/.+/' => [
                static::IS_REGULAR_EXPRESSION => true,
                static::METHODS => [
                    'get',
                ],
            ],
        ]);
        $routeTransfer = (new RouteTransfer())
            ->setRoute('/testRoute/testId')
            ->setMethod('get');

        // Act
        $result = $glueStorefrontApiApplicationAuthorizationConnectorClient->isProtected($routeTransfer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testExpandApiApplicationSchemaContextDeclaredMethodsIsProtected(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = (new ApiApplicationSchemaContextTransfer())
            ->addResourceContext((new ResourceContextTransfer())
                ->setResourceType('test')
                ->setDeclaredMethods((new GlueResourceMethodCollectionTransfer())
                    ->setPost((new GlueResourceMethodConfigurationTransfer())
                        ->setAction('postAction'))));
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/test' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);

        //Act
        $apiApplicationSchemaContextTransfer = $glueStorefrontApiApplicationAuthorizationConnectorClient->expandApiApplicationSchemaContext($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertTrue($apiApplicationSchemaContextTransfer->getResourceContexts()->getArrayCopy()[0]->getDeclaredMethods()->getPost()->getIsProtected());
    }

    /**
     * @return void
     */
    public function testExpandApiApplicationSchemaContextDeclaredMethodsIsNotProtected(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = (new ApiApplicationSchemaContextTransfer())
            ->addResourceContext((new ResourceContextTransfer())
                ->setResourceType('test')
                ->setDeclaredMethods((new GlueResourceMethodCollectionTransfer())
                    ->setPost((new GlueResourceMethodConfigurationTransfer())
                        ->setAction('postAction'))));
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/testRoute' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);

        //Act
        $apiApplicationSchemaContextTransfer = $glueStorefrontApiApplicationAuthorizationConnectorClient->expandApiApplicationSchemaContext($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertFalse($apiApplicationSchemaContextTransfer->getResourceContexts()->getArrayCopy()[0]->getDeclaredMethods()->getPost()->getIsProtected());
    }

    /**
     * @return void
     */
    public function testExpandApiApplicationSchemaContextCustomRouteIsProtected(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = (new ApiApplicationSchemaContextTransfer())
            ->addCustomRoutesContext((new CustomRoutesContextTransfer())
                ->setPath('/test')
                ->setDefaults(['_method' => 'get']));
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/test' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);

        //Act
        $apiApplicationSchemaContextTransfer = $glueStorefrontApiApplicationAuthorizationConnectorClient->expandApiApplicationSchemaContext($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertTrue($apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->getArrayCopy()[0]->getIsProtected());
    }

    /**
     * @return void
     */
    public function testExpandApiApplicationSchemaContextCustomRouteIsNotProtected(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = (new ApiApplicationSchemaContextTransfer())
            ->addCustomRoutesContext((new CustomRoutesContextTransfer())
                ->setPath('/test')
                ->setDefaults(['_method' => 'get']));
        $glueStorefrontApiApplicationAuthorizationConnectorClient = $this->getGlueStorefrontApiApplicationAuthorizationConnectorClient([
            '/testRoute' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);

        //Act
        $apiApplicationSchemaContextTransfer = $glueStorefrontApiApplicationAuthorizationConnectorClient->expandApiApplicationSchemaContext($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertFalse($apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->getArrayCopy()[0]->getIsProtected());
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return \Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorClient
     */
    protected function getGlueStorefrontApiApplicationAuthorizationConnectorClient(array $data): GlueStorefrontApiApplicationAuthorizationConnectorClient
    {
        $glueStorefrontApiApplicationAuthorizationConnectorConfigMock = $this->createGlueStorefrontApiApplicationAuthorizationConnectorConfigMock();
        $glueStorefrontApiApplicationAuthorizationConnectorConfigMock
            ->method(static::CONFIG_METHOD_NAME)
            ->willReturn($data);

        $glueStorefrontApiApplicationAuthorizationConnectorFactory = $this->tester->createGlueStorefrontApiApplicationAuthorizationConnectorFactory();
        $glueStorefrontApiApplicationAuthorizationConnectorFactory->setConfig($glueStorefrontApiApplicationAuthorizationConnectorConfigMock);

        $glueStorefrontApiApplicationAuthorizationConnectorClient = new GlueStorefrontApiApplicationAuthorizationConnectorClient();

        return $glueStorefrontApiApplicationAuthorizationConnectorClient->setFactory($glueStorefrontApiApplicationAuthorizationConnectorFactory);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorConfig
     */
    protected function createGlueStorefrontApiApplicationAuthorizationConnectorConfigMock(): GlueStorefrontApiApplicationAuthorizationConnectorConfig
    {
        return $this->getMockBuilder(GlueStorefrontApiApplicationAuthorizationConnectorConfig::class)->getMock();
    }
}
