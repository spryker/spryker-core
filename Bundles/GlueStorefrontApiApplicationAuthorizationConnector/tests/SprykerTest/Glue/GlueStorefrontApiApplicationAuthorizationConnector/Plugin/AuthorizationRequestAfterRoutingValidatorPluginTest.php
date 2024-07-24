<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use Generated\Shared\Transfer\GlueRequestCustomerTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Dependency\Client\GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientBridge;
use Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Dependency\Client\GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientInterface;
use Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorDependencyProvider;
use Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Plugin\GlueStorefrontApiApplicationAuthorizationConnector\AuthorizationRequestAfterRoutingValidatorPlugin;
use SprykerTest\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Stub\TestAuthorizationStrategyAwareResourceRoutePlugin;
use SprykerTest\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Stub\TestDefaultAuthorizationStrategyAwareResourceRoutePlugin;
use SprykerTest\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Stub\TestUnsupportResourcePlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplicationAuthorizationConnector
 * @group Plugin
 * @group AuthorizationRequestAfterRoutingValidatorPluginTest
 * Add your own group annotations below this line
 */
class AuthorizationRequestAfterRoutingValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const NATURAL_IDENTIFIER = 'test identifier';

    /**
     * @uses \SprykerTest\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Stub\TestDefaultAuthorizationStrategyAwareResourceRoutePlugin::STRATEGY_NAME
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
            GlueStorefrontApiApplicationAuthorizationConnectorDependencyProvider::CLIENT_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(true),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();
        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestCustomerTransfer = (new GlueRequestCustomerTransfer())->setNaturalIdentifier(static::NATURAL_IDENTIFIER);
            $glueRequestTransfer->setRequestCustomer($glueRequestCustomerTransfer);

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
            GlueStorefrontApiApplicationAuthorizationConnectorDependencyProvider::CLIENT_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(false),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();
        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestCustomerTransfer = (new GlueRequestCustomerTransfer())->setNaturalIdentifier(static::NATURAL_IDENTIFIER);
        $glueRequestTransfer->setRequestCustomer($glueRequestCustomerTransfer);
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
            GlueStorefrontApiApplicationAuthorizationConnectorDependencyProvider::CLIENT_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(true),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();
        $glueRequestTransfer = (new GlueRequestTransfer())->setMethod('get');
        $glueRequestCustomerTransfer = (new GlueRequestCustomerTransfer())->setNaturalIdentifier(static::NATURAL_IDENTIFIER);
        $glueRequestTransfer->setRequestCustomer($glueRequestCustomerTransfer);

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
            GlueStorefrontApiApplicationAuthorizationConnectorDependencyProvider::CLIENT_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(false),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();

        $glueRequestCustomerTransfer = (new GlueRequestCustomerTransfer())->setNaturalIdentifier(static::NATURAL_IDENTIFIER);
        $glueRequestTransfer = (new GlueRequestTransfer())->setMethod('get');
        $glueRequestTransfer->setRequestCustomer($glueRequestCustomerTransfer);
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
            GlueStorefrontApiApplicationAuthorizationConnectorDependencyProvider::CLIENT_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(true),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();
        $glueRequestTransfer = new GlueRequestTransfer();
        $glueRequestCustomerTransfer = new GlueRequestCustomerTransfer();
        $glueRequestTransfer->setRequestCustomer($glueRequestCustomerTransfer);
        $stubResource = new TestUnsupportResourcePlugin();

        //Act
        $glueRequestValidationTransfer = $plugin->validate($glueRequestTransfer, $stubResource);

        //Assert
        $this->assertTrue($glueRequestValidationTransfer->getIsValid());
    }

    /**
     * @return void
     */
    public function testValidateReturnsIsNotValidWhenRouteNotProvided(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueStorefrontApiApplicationAuthorizationConnectorDependencyProvider::CLIENT_AUTHORIZATION,
            $this->mockAuthorizationClientBridge(false),
        );
        $plugin = new AuthorizationRequestAfterRoutingValidatorPlugin();
        $glueRequestTransfer = (new GlueRequestTransfer())->setMethod('post');
        $glueRequestCustomerTransfer = new GlueRequestCustomerTransfer();
        $glueRequestTransfer->setRequestCustomer($glueRequestCustomerTransfer);
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
     * @param bool $isAuthorized
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Dependency\Client\GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientInterface
     */
    protected function mockAuthorizationClientBridge(bool $isAuthorized): GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientInterface
    {
        $authorizationResponseTransfer = (new AuthorizationResponseTransfer())->setIsAuthorized($isAuthorized);
        if (!$isAuthorized) {
            $authorizationResponseTransfer->setFailedStrategy(static::STRATEGY_NAME);
        }
        $authorizationClientBridge = $this->getMockBuilder(GlueStorefrontApiApplicationAuthorizationConnectorToAuthorizationClientBridge::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['authorize'])
            ->getMock();
        $authorizationClientBridge->method('authorize')
            ->willReturn($authorizationResponseTransfer);

        return $authorizationClientBridge;
    }
}
