<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Plugin\Backend;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Spryker\Glue\GlueApplication\Exception\ControllerNotFoundException;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\Backend\AbstractResourcePlugin;
use SprykerTest\Glue\GlueApplication\Stub\ResourceController;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Plugin
 * @group Backend
 * @group BackendAbstractResourcePluginTest
 * Add your own group annotations below this line
 */
class BackendAbstractResourcePluginTest extends Unit
{
    /**
     * @return void
     */
    public function testGetResourceReturnsCallableResourceIfEmptyGlueResourceMethodConfigurationTransferExists(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())
                ->setMethod('getCollection'));

        $abstractResourcePluginMock = $this->getMockBuilder(AbstractResourcePlugin::class)
            ->onlyMethods(['getDeclaredMethods', 'getController', 'getType'])
            ->getMock();
        $abstractResourcePluginMock
            ->expects($this->once())
            ->method('getDeclaredMethods')
            ->willReturn((new GlueResourceMethodCollectionTransfer())
                ->setGetCollection(new GlueResourceMethodConfigurationTransfer()));
        $abstractResourcePluginMock
            ->expects($this->once())
            ->method('getController')
            ->willReturn(ResourceController::class);

        //Act
        $resource = $abstractResourcePluginMock->getResource($glueRequestTransfer);

        //Assert
        $this->assertTrue(is_callable($resource));
        $this->assertSame('getCollectionAction', $resource[1]);
        $this->assertInstanceOf(ResourceController::class, $resource[0]);
    }

    /**
     * @return void
     */
    public function testGetResourceReturnsCallableResourceIfConfiguredGlueResourceMethodConfigurationTransferExists(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())
                ->setMethod('getCollection'));

        $abstractResourcePluginMock = $this->getMockBuilder(AbstractResourcePlugin::class)
            ->onlyMethods(['getDeclaredMethods', 'getController', 'getType'])
            ->getMock();
        $abstractResourcePluginMock
            ->expects($this->once())
            ->method('getDeclaredMethods')
            ->willReturn((new GlueResourceMethodCollectionTransfer())
                ->setGetCollection((new GlueResourceMethodConfigurationTransfer())
                    ->setController(ResourceController::class)
                    ->setAction('getCollectionAction')));
        $abstractResourcePluginMock
            ->expects($this->never())
            ->method('getController');

        //Act
        $resource = $abstractResourcePluginMock->getResource($glueRequestTransfer);

        //Assert
        $this->assertTrue(is_callable($resource));
        $this->assertSame('getCollectionAction', $resource[1]);
        $this->assertInstanceOf(ResourceController::class, $resource[0]);
    }

    /**
     * @return void
     */
    public function testGetResourceThrowsExceptionIfControllerDoesNotExist(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())
                ->setMethod('getCollection'));

        $abstractResourcePluginMock = $this->getMockBuilder(AbstractResourcePlugin::class)
            ->onlyMethods(['getDeclaredMethods', 'getController', 'getType'])
            ->getMock();
        $abstractResourcePluginMock
            ->expects($this->once())
            ->method('getDeclaredMethods')
            ->willReturn((new GlueResourceMethodCollectionTransfer())
                ->setGetCollection(new GlueResourceMethodConfigurationTransfer()));
        $abstractResourcePluginMock
            ->expects($this->once())
            ->method('getController')
            ->willReturn('SprykerTest\Glue\GlueApplication\Stub\FakeResourceController');

        //Assert
        $this->expectException(ControllerNotFoundException::class);
        $this->expectExceptionMessage('Controller not found!');

        //Act
        $resource = $abstractResourcePluginMock->getResource($glueRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGetResourceReturnsCallableResourceIfGlueResourceMethodConfigurationTransferDoesNotExist(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource((new GlueResourceTransfer())
                ->setMethod('getCollection'));

        $abstractResourcePluginMock = $this->getMockBuilder(AbstractResourcePlugin::class)
            ->onlyMethods(['getDeclaredMethods', 'getController', 'getType'])
            ->getMock();
        $abstractResourcePluginMock
            ->expects($this->once())
            ->method('getDeclaredMethods')
            ->willReturn(new GlueResourceMethodCollectionTransfer());
        $abstractResourcePluginMock
            ->expects($this->once())
            ->method('getController')
            ->willReturn(ResourceController::class);

        //Act
        $resource = $abstractResourcePluginMock->getResource($glueRequestTransfer);

        //Assert
        $this->assertTrue(is_callable($resource));
        $this->assertSame('getCollectionAction', $resource[1]);
        $this->assertInstanceOf(ResourceController::class, $resource[0]);
    }
}
