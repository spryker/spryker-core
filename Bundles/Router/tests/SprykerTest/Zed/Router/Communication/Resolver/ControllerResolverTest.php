<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Router\tests\SprykerTest\Zed\Router\Communication\Resolver;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group Router
 * @group tests
 * @group SprykerTest
 * @group Zed
 * @group Router
 * @group Communication
 * @group Resolver
 * @group ControllerResolverTest
 * Add your own group annotations below this line
 */
class ControllerResolverTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Router\RouterCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetControllerReturnsAnArrayWhenControllerIsAnUrl(): void
    {
        // Arrange
        $request = $this->tester->getRequestWithControllerUrl();
        require_once codecept_data_dir('Fixtures/Controller/MockController.php');

        // Act
        $controller = $this->tester->getControllerResolver()->getController($request);

        // Assert
        $this->tester->assertController($this->tester->getMockControllerNamespace(), $controller);
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
}
