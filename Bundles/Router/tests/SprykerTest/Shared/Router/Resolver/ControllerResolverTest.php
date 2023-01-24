<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Router\Communication\Resolver;

use Codeception\Test\Unit;
use InvalidArgumentException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Router
 * @group Communication
 * @group Resolver
 * @group ControllerResolverTest
 * Add your own group annotations below this line
 */
class ControllerResolverTest extends Unit
{
    /**
     * @var \SprykerTest\Shared\Router\RouterTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetControllerReturnsFalseWhenControllerNotInRequestAttributes(): void
    {
        // Arrange
        $request = $this->tester->getRequest();

        // Act
        $controller = $this->tester->getControllerResolver()->getController($request);

        // Assert
        $this->assertFalse($controller);
    }

    /**
     * @return void
     */
    public function testGetControllerReturnsFalseWhenControllerIsNotAStringNotAnArrayAndNotAnObject(): void
    {
        // Arrange
        $request = $this->tester->getRequestWithUnresolvableController();

        // Act
        $controller = $this->tester->getControllerResolver()->getController($request);

        // Assert
        $this->assertFalse($controller);
    }

    /**
     * @return void
     */
    public function testGetControllerReturnsAnArrayWhenControllerIsAService(): void
    {
        require_once codecept_data_dir('Fixtures/Controller/MockController.php');

        // Arrange
        $request = $this->tester->getRequestWithControllerService();
        $services = ['ControllerServiceName' => $this->tester->getMockControllerNamespace()];

        // Act
        $controller = $this->tester->getControllerResolver($services)->getController($request);

        // Assert
        $this->tester->assertController($this->tester->getMockControllerNamespace(), $controller);
    }

    /**
     * @return void
     */
    public function testGetControllerReturnsFalseWhenControllerIsAServiceButNotFoundInContainer(): void
    {
        // Arrange
        $request = $this->tester->getRequestWithControllerService();

        // Act
        $controller = $this->tester->getControllerResolver()->getController($request);

        // Assert
        $this->assertFalse($controller);
    }

    /**
     * @return void
     */
    public function testGetControllerReturnsFalseWhenControllerIsInvalidString(): void
    {
        // Arrange
        $request = $this->tester->getRequestWithInvalidControllerString();

        // Act
        $controller = $this->tester->getControllerResolver()->getController($request);

        // Assert
        $this->assertFalse($controller);
    }

    /**
     * @return void
     */
    public function testGetControllerReturnsAnArrayWhenControllerIsAnArrayAndIsCallable(): void
    {
        require_once codecept_data_dir('Fixtures/Controller/MockController.php');

        // Arrange
        $request = $this->tester->getRequestWithCallableController();

        // Act
        $controller = $this->tester->getControllerResolver()->getController($request);

        // Assert
        $this->tester->assertController($this->tester->getMockControllerNamespace(), $controller);
    }

    /**
     * @return void
     */
    public function testGetControllerReturnsAnArrayWhenControllerIsAnArrayAndIsNotCallable(): void
    {
        // Arrange
        $request = $this->tester->getRequestWithInstantiableClass();

        // Act
        $controller = $this->tester->getControllerResolver()->getController($request);

        // Assert
        $this->tester->assertController($this->tester->getMockControllerNamespace(), $controller);
    }

    /**
     * @return void
     */
    public function testGetControllerReturnsAnInvokedObjectWhenControllerIsAnObjectAndIsInvokable(): void
    {
        // Arrange
        $request = $this->tester->getRequestWithInvokableControllerObject();

        // Act
        $this->tester->getControllerResolver()->getController($request);

        // Assert
        $this->tester->assertInvokeCalledOnController();
    }

    /**
     * @return void
     */
    public function testGetControllerThrowsAnExceptionWhenControllerIsAnObjectAndIsNotInvokable(): void
    {
        // Arrange
        $request = $this->tester->getRequestWithNotInvokableControllerObject();

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $this->tester->getControllerResolver()->getController($request);
    }

    /**
     * @return void
     */
    public function testGetControllerInjectsAndInitializesWhenMethodsExist(): void
    {
        // Arrange
        $request = $this->tester->getRequestWithInvokableControllerObject();

        // Act
        $this->tester->getControllerResolver()->getController($request);

        // Assert
        $this->tester->assertSetApplicationAndInitializeCalledOnController();
    }
}
