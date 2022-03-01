<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\Router;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueBackendApiApplication\Resource\GenericResource;
use Spryker\Glue\GlueBackendApiApplication\Resource\MissingResource;
use Spryker\Glue\GlueBackendApiApplication\Router\ChainRouterInterface;
use Spryker\Glue\GlueBackendApiApplication\Router\RequestRoutingMatcher;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface;
use SprykerTest\Glue\GlueBackendApiApplication\Stub\ResourceController;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplication
 * @group Router
 * @group RequestRoutingMatcherTest
 * Add your own group annotations below this line
 */
class RequestRoutingMatcherTest extends Unit
{
    /**
     * @return void
     */
    public function testMatchRequestReturnsMissingResourceIfResourceDoesNotExistAfterRouteResource(): void
    {
        //Arrange
        $chainRouterMock = $this->createMock(ChainRouterInterface::class);
        $chainRouterMock
            ->expects($this->once())
            ->method('routeResource')
            ->willReturn(new GlueRequestTransfer());

        $requestResourceFilterPluginMock = $this->createMock(RequestResourceFilterPluginInterface::class);
        $requestResourceFilterPluginMock
            ->expects($this->never())
            ->method('filterResource');

        $resourceMock = $this->createMock(ResourceInterface::class);

        $requestRoutingMatcher = new RequestRoutingMatcher(
            $chainRouterMock,
            $requestResourceFilterPluginMock,
        );

        //Act
        $resource = $requestRoutingMatcher->matchRequest(new GlueRequestTransfer(), [$resourceMock]);

        //Assert
        $this->assertInstanceOf(MissingResource::class, $resource);
    }

    /**
     * @return void
     */
    public function testMatchRequestReturnsGenericResourceIfResourceNameDoesNotExistAndResourceControllerExist(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource(
                (new GlueResourceTransfer())->setController([new ResourceController(), 'getCollectionAction']),
            );

        $chainRouterMock = $this->createMock(ChainRouterInterface::class);
        $chainRouterMock
            ->expects($this->once())
            ->method('routeResource')
            ->willReturn($glueRequestTransfer);

        $requestResourceFilterPluginMock = $this->createMock(RequestResourceFilterPluginInterface::class);
        $requestResourceFilterPluginMock
            ->expects($this->never())
            ->method('filterResource');

        $resourceMock = $this->createMock(ResourceInterface::class);

        $requestRoutingMatcher = new RequestRoutingMatcher(
            $chainRouterMock,
            $requestResourceFilterPluginMock,
        );

        //Act
        $resource = $requestRoutingMatcher->matchRequest(new GlueRequestTransfer(), [$resourceMock]);

        //Assert
        $this->assertInstanceOf(GenericResource::class, $resource);
    }

    /**
     * @return void
     */
    public function testMatchRequestReturnsMissingResourceIfResourceDoesNotExistAfterFilterResource(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource(
                (new GlueResourceTransfer())
                    ->setController([new ResourceController(), 'getCollectionAction'])
                    ->setResourceName('Foo'),
            );

        $chainRouterMock = $this->createMock(ChainRouterInterface::class);
        $chainRouterMock
            ->expects($this->once())
            ->method('routeResource')
            ->willReturn($glueRequestTransfer);

        $requestResourceFilterPluginMock = $this->createMock(RequestResourceFilterPluginInterface::class);
        $requestResourceFilterPluginMock
            ->expects($this->once())
            ->method('filterResource')
            ->willReturn(null);

        $resourceMock = $this->createMock(ResourceInterface::class);

        $requestRoutingMatcher = new RequestRoutingMatcher(
            $chainRouterMock,
            $requestResourceFilterPluginMock,
        );

        //Act
        $resource = $requestRoutingMatcher->matchRequest(new GlueRequestTransfer(), [$resourceMock]);

        //Assert
        $this->assertInstanceOf(MissingResource::class, $resource);
    }

    /**
     * @return void
     */
    public function testMatchRequestReturnsResource(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setResource(
                (new GlueResourceTransfer())
                    ->setController([new ResourceController(), 'getCollectionAction'])
                    ->setResourceName('Foo'),
            );

        $chainRouterMock = $this->createMock(ChainRouterInterface::class);
        $chainRouterMock
            ->expects($this->once())
            ->method('routeResource')
            ->willReturn($glueRequestTransfer);

        $resourceMock = $this->createMock(ResourceInterface::class);

        $requestResourceFilterPluginMock = $this->createMock(RequestResourceFilterPluginInterface::class);
        $requestResourceFilterPluginMock
            ->expects($this->once())
            ->method('filterResource')
            ->willReturn($resourceMock);

        $requestRoutingMatcher = new RequestRoutingMatcher(
            $chainRouterMock,
            $requestResourceFilterPluginMock,
        );

        //Act
        $resource = $requestRoutingMatcher->matchRequest(new GlueRequestTransfer(), [$resourceMock]);

        //Assert
        $this->assertInstanceOf(ResourceInterface::class, $resource);
        $this->assertNotInstanceOf(MissingResource::class, $resource);
        $this->assertNotInstanceOf(GenericResource::class, $resource);
    }
}
