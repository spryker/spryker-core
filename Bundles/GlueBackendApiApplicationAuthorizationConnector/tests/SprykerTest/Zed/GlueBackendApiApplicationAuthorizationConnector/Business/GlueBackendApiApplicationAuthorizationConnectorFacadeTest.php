<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GlueBackendApiApplicationAuthorizationConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\AuthorizationIdentityTransfer;
use Generated\Shared\Transfer\CustomRoutesContextTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Generated\Shared\Transfer\RouteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GlueBackendApiApplicationAuthorizationConnector
 * @group Business
 * @group Facade
 * @group GlueBackendApiApplicationAuthorizationConnectorFacadeTest
 * Add your own group annotations below this line
 */
class GlueBackendApiApplicationAuthorizationConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\GlueBackendApiApplicationAuthorizationConnector\GlueBackendApiApplicationAuthorizationConnectorTester
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
    protected const GLUE_REQUEST_USER = 'glueRequestUser';

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
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, []);
        $authorizationRequestTransfer = $this->tester->createAuthorizationRequestTransfer([
            static::METHOD => 'get',
            static::PATH => 'testRoute',
            static::GLUE_REQUEST_USER => new GlueRequestUserTransfer(),
        ]);

        // Act
        $result = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsFalseForProtectedEndpointWhichDoesNotContainMethodData(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
                '/test' => [
                    static::IS_REGULAR_EXPRESSION => true,
                ],
        ]);
        $authorizationRequestTransfer = $this->tester->createAuthorizationRequestTransfer([
            static::PATH => '/testRoute',
            static::GLUE_REQUEST_USER => new GlueRequestUserTransfer(),
        ]);

        // Act
        $result = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsFalseForProtectedEndpointWhichDoesNotContainPathData(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
            '/test' => [
                static::IS_REGULAR_EXPRESSION => true,
            ],
        ]);
        $authorizationRequestTransfer = $this->tester->createAuthorizationRequestTransfer([
            static::METHOD => 'get',
            static::GLUE_REQUEST_USER => new GlueRequestUserTransfer(),
        ]);

        // Act
        $result = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsFalseForProtectedEndpointWhenMethodIsProtectedByPathDoesNotContainValidToken(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
            '/testRoute' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);
        $authorizationRequestTransfer = $this->tester->createAuthorizationRequestTransfer([
            static::METHOD => 'post',
            static::PATH => '/testRoute',
        ]);

        // Act
        $result = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsTrueForProtectedEndpointWhenMethodIsProtectedByPath(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
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
        ], (new AuthorizationIdentityTransfer())->setIdentifier(1));

        // Act
        $result = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsFalseForProtectedEndpointWhenMethodIsProtectedByRegularExpressionAndDoesNotContainValidToken(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
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
        $result = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeReturnsTrueForProtectedEndpointWhenMethodIsProtectedByRegularExpression(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
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
        ], (new AuthorizationIdentityTransfer())->setIdentifier(1));

        // Act
        $result = $this->tester->getFacade()->authorize($authorizationRequestTransfer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsProtectedWhenMethodIsProtectedByTheFullyQualifiedPathName(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
            '/testRoute' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);
        $routeTransfer = (new RouteTransfer())
            ->setRoute('/testRoute')
            ->setMethod('post');

        // Act
        $result = $this->tester->getFacade()->isProtected($routeTransfer);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testIsProtectedWhenMethodIsNotProtectedByTheFullyQualifiedPathName(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
            '/testRoute' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);
        $routeTransfer = (new RouteTransfer())
            ->setRoute('/route')
            ->setMethod('post');

        // Act
        $result = $this->tester->getFacade()->isProtected($routeTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testIsProtectedWhenMethodIsNotProtectedByRegularExpression(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
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
        $result = $this->tester->getFacade()->isProtected($routeTransfer);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testIsProtectedWhenMethodIsProtectedByRegularExpression(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
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
        $result = $this->tester->getFacade()->isProtected($routeTransfer);

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
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
            '/test' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);

        //Act
        $apiApplicationSchemaContextTransfer = $this->tester->getFacade()->expandApiApplicationSchemaContext($apiApplicationSchemaContextTransfer);

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
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
            '/testRoute' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);

        //Act
        $apiApplicationSchemaContextTransfer = $this->tester->getFacade()->expandApiApplicationSchemaContext($apiApplicationSchemaContextTransfer);

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
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
            '/test' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);

        //Act
        $apiApplicationSchemaContextTransfer = $this->tester->getFacade()->expandApiApplicationSchemaContext($apiApplicationSchemaContextTransfer);

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
        $this->tester->mockConfigMethod(static::CONFIG_METHOD_NAME, [
            '/testRoute' => [
                static::IS_REGULAR_EXPRESSION => false,
            ],
        ]);

        //Act
        $apiApplicationSchemaContextTransfer = $this->tester->getFacade()->expandApiApplicationSchemaContext($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertFalse($apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->getArrayCopy()[0]->getIsProtected());
    }
}
