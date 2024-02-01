<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplicationAuthorizationConnector\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Dependency\Facade\GlueBackendApiApplicationAuthorizationConnectorToAuthorizationFacadeBridge;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Dependency\Facade\GlueBackendApiApplicationAuthorizationConnectorToAuthorizationFacadeInterface;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\GlueBackendApiApplicationAuthorizationConnectorDependencyProvider;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Plugin\GlueBackendApiApplication\AuthorizationRequestAfterRoutingValidatorPlugin;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin\ProtectedRouteAuthorizationConfigProviderPluginInterface;
use Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\GlueBackendApiApplicationAuthorizationConnectorFacade;
use Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\GlueBackendApiApplicationAuthorizationConnectorFacadeInterface;
use SprykerTest\Glue\GlueBackendApiApplicationAuthorizationConnector\Stub\TestAuthorizationStrategyAwareResourceRoutePlugin;
use SprykerTest\Glue\GlueBackendApiApplicationAuthorizationConnector\Stub\TestDefaultAuthorizationStrategyAwareResourceRoutePlugin;
use SprykerTest\Glue\GlueBackendApiApplicationAuthorizationConnector\Stub\TestUnsupportResourcePlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplicationAuthorizationConnector
 * @group Plugin
 * @group AuthorizationRequestAfterRoutingValidatorPluginTest
 * Add your own group annotations below this line
 */
class AuthorizationRequestAfterRoutingValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueBackendApiApplicationAuthorizationConnector\GlueBackendApiApplicationAuthorizationConnectorTester
     */
    protected $tester;

    /**
     * @var int
     */
    protected const SURROGATE_IDENTIFIER = 12345;

    /**
     * @uses \SprykerTest\Glue\GlueBackendApiApplicationAuthorizationConnector\Stub\TestDefaultAuthorizationStrategyAwareResourceRoutePlugin::STRATEGY_NAME
     *
     * @var string
     */
    protected const STRATEGY_NAME = 'test';

    /**
     * @return void
     */
    public function testValidateRequestUseDefaultAuthorizationStrategyAwareResourceRoutePluginIsValid(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::FACADE_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(true),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();
        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestUserTransfer = (new GlueRequestUserTransfer())->setSurrogateIdentifier(static::SURROGATE_IDENTIFIER);
        $glueRequestTransfer->setRequestUser($glueRequestUserTransfer);

        $stubResource = new TestDefaultAuthorizationStrategyAwareResourceRoutePlugin();

        //Act
        $glueRequestValidationTransfer = $plugin->validate($glueRequestTransfer, $stubResource);

        //Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateRequestUseDefaultAuthorizationStrategyAwareResourceRoutePluginNotValid(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::FACADE_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(false),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();
        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestUserTransfer = (new GlueRequestUserTransfer())->setSurrogateIdentifier(static::SURROGATE_IDENTIFIER);
        $glueRequestTransfer->setRequestUser($glueRequestUserTransfer);
        $stubResource = new TestDefaultAuthorizationStrategyAwareResourceRoutePlugin();

        //Act
        $glueRequestValidationTransfer = $plugin->validate($glueRequestTransfer, $stubResource);

        //Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertEquals(Response::HTTP_FORBIDDEN, $glueRequestValidationTransfer->getStatus());
        $this->assertEquals('Unauthorized request.', $glueRequestValidationTransfer->getValidationError());
    }

    /**
     * @return void
     */
    public function testValidateRequestUseAuthorizationStrategyAwareResourceRoutePluginIsValid(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::FACADE_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(true),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();
        $glueRequestTransfer = (new GlueRequestTransfer())->setMethod('get');
        $glueRequestUserTransfer = (new GlueRequestUserTransfer())->setSurrogateIdentifier(static::SURROGATE_IDENTIFIER);
        $glueRequestTransfer->setRequestUser($glueRequestUserTransfer);

        $stubResource = new TestAuthorizationStrategyAwareResourceRoutePlugin();

        //Act
        $glueRequestValidationTransfer = $plugin->validate($glueRequestTransfer, $stubResource);

        //Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateRequestUseAuthorizationStrategyAwareResourceRoutePluginNotValid(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::FACADE_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(false),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();

        $glueRequestTransfer = (new GlueRequestTransfer())->setMethod('get');
        $glueRequestUserTransfer = (new GlueRequestUserTransfer())->setSurrogateIdentifier(static::SURROGATE_IDENTIFIER);
        $glueRequestTransfer->setRequestUser($glueRequestUserTransfer);
        $stubResource = new TestDefaultAuthorizationStrategyAwareResourceRoutePlugin();

        //Act
        $glueRequestValidationTransfer = $plugin->validate($glueRequestTransfer, $stubResource);

        //Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
        $this->assertEquals(Response::HTTP_FORBIDDEN, $glueRequestValidationTransfer->getStatus());
        $this->assertEquals('Unauthorized request.', $glueRequestValidationTransfer->getValidationError());
    }

    /**
     * @return void
     */
    public function testValidateRequestUnsupportedResourceRouteException(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::FACADE_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(true),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();
        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestUserTransfer = (new GlueRequestUserTransfer())->setSurrogateIdentifier(static::SURROGATE_IDENTIFIER);
        $glueRequestTransfer->setRequestUser($glueRequestUserTransfer);
        $stubResource = new TestUnsupportResourcePlugin();

        //Act
        $glueRequestValidationTransfer = $plugin->validate($glueRequestTransfer, $stubResource);

        //Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateRequestMissingRouteNotValid(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::FACADE_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(false),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();
        $glueRequestTransfer = (new GlueRequestTransfer())->setMethod('post');
        $glueRequestUserTransfer = (new GlueRequestUserTransfer())->setSurrogateIdentifier(static::SURROGATE_IDENTIFIER);
        $glueRequestTransfer->setRequestUser($glueRequestUserTransfer);
        $stubResource = new TestAuthorizationStrategyAwareResourceRoutePlugin();

        //Act
        $glueRequestValidationTransfer = $plugin->validate($glueRequestTransfer, $stubResource);

        //Assert
        $this->assertFalse($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testShouldSkipValidationForOptionsPreflightRequest(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setMethod(Request::METHOD_OPTIONS);
        $stubResource = new TestDefaultAuthorizationStrategyAwareResourceRoutePlugin();

        //Act
        $glueRequestValidationTransfer = (new AuthorizationRequestAfterRoutingValidatorPlugin())->validate($glueRequestTransfer, $stubResource);

        //Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testEnsureThatProtectedRouteAuthorizationConfigProviderPluginStackExecuted(): void
    {
        // Arrange
        $this->tester->setDependency(
            GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::FACADE_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(false),
        );
        $this->tester->setDependency(
            GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::FACADE_GLUE_BACKEND_API_APPLICATION_AUTHORIZATION_CONNECTOR,
            $this->mockGlueBackendApiApplicationAuthorizationConnectorFacade(true)
        );

        $glueRequestTransfer = (new GlueRequestTransfer())->setMethod(Request::METHOD_POST);
        $glueRequestUserTransfer = (new GlueRequestUserTransfer())->setSurrogateIdentifier(static::SURROGATE_IDENTIFIER);
        $glueRequestTransfer->setRequestUser($glueRequestUserTransfer);
        $stubResource = new TestDefaultAuthorizationStrategyAwareResourceRoutePlugin();

        $this->tester->setDependency(
            GlueBackendApiApplicationAuthorizationConnectorDependencyProvider::PLUGINS_PROTECTED_ROUTE_AUTHORIZATION_CONFIG_PROVIDER,
            [$this->getProtectedRouteAuthorizationConfigProviderPluginMock()],
        );

        // Act
        (new AuthorizationRequestAfterRoutingValidatorPlugin())->validate($glueRequestTransfer, $stubResource);
    }

    /**
     * @param bool $isAuthorized
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Dependency\Facade\GlueBackendApiApplicationAuthorizationConnectorToAuthorizationFacadeInterface
     */
    protected function mockAuthorizationClientBridge(bool $isAuthorized): GlueBackendApiApplicationAuthorizationConnectorToAuthorizationFacadeInterface
    {
        $authorizationResponseTransfer = (new AuthorizationResponseTransfer())->setIsAuthorized($isAuthorized);
        if (!$isAuthorized) {
            $authorizationResponseTransfer->setFailedStrategy(static::STRATEGY_NAME);
        }
        $authorizationClientBridge = $this->getMockBuilder(GlueBackendApiApplicationAuthorizationConnectorToAuthorizationFacadeBridge::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['authorize'])
            ->getMock();
        $authorizationClientBridge->method('authorize')
            ->willReturn($authorizationResponseTransfer);

        return $authorizationClientBridge;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin\ProtectedRouteAuthorizationConfigProviderPluginInterface
     */
    protected function getProtectedRouteAuthorizationConfigProviderPluginMock(): ProtectedRouteAuthorizationConfigProviderPluginInterface
    {
        $protectedRouteAuthorizationConfigProviderPluginMock = $this
            ->getMockBuilder(ProtectedRouteAuthorizationConfigProviderPluginInterface::class)
            ->onlyMethods(['provide'])
            ->getMock();

        $protectedRouteAuthorizationConfigProviderPluginMock
            ->expects($this->once())
            ->method('provide')
            ->willReturn(new RouteAuthorizationConfigTransfer());

        return $protectedRouteAuthorizationConfigProviderPluginMock;
    }

    /**
     * @param bool $isProtected
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|GlueBackendApiApplicationAuthorizationConnectorFacadeInterface
     */
    protected function mockGlueBackendApiApplicationAuthorizationConnectorFacade(bool $isProtected): GlueBackendApiApplicationAuthorizationConnectorFacadeInterface
    {
        $glueBackendApiApplicationAuthorizationConnectorFacadeMock = $this->getMockBuilder(GlueBackendApiApplicationAuthorizationConnectorFacade::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isProtected'])
            ->getMock();

        $glueBackendApiApplicationAuthorizationConnectorFacadeMock
            ->expects($this->once())
            ->method('isProtected')
            ->willReturn($isProtected);

        return $glueBackendApiApplicationAuthorizationConnectorFacadeMock;
    }
}
