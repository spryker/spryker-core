<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\Router;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueBackendApiApplication\Router\ChainRouter;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouterPluginInterface;
use Symfony\Component\Routing\Exception\ExceptionInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplication
 * @group Router
 * @group ChainRouterTest
 * Add your own group annotations below this line
 */
class ChainRouterTest extends Unit
{
    /**
     * @var string
     */
    protected const HTTP_PATH = '/foo';

    /**
     * @var string
     */
    protected const HTTP_METHOD = 'OPTIONS';

    /**
     * @var string
     */
    protected const HTTP_HOST = 'glue-backend.de.spryker.local';

    /**
     * @return void
     */
    public function testRouteResourceReturnsGlueRequestTransferExpandedWithResource(): void
    {
        //Arrange
        $matchResult = [
            '_resourceName' => 'fooResource',
            '_method' => 'fooMethod',
            '_controller' => ['fooController', 'fooAction'],
            '_action' => 'fooAction',
        ];

        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setPath(static::HTTP_PATH)
            ->setMethod(static::HTTP_METHOD)
            ->setHost(static::HTTP_HOST);

        $routerPluginInterface = $this->createMock(RouterPluginInterface::class);

        $chainRouterMock = $this->getMockBuilder(ChainRouter::class)
            ->setConstructorArgs([[$routerPluginInterface]])
            ->setMethods(['filterParameters', 'setContext', 'match'])
            ->getMock();
        $chainRouterMock
            ->expects($this->once())
            ->method('setContext');
        $chainRouterMock
            ->expects($this->once())
            ->method('match')
            ->willReturn($matchResult);
        $chainRouterMock
            ->expects($this->once())
            ->method('filterParameters')
            ->willReturn($matchResult);

        //Act
        $glueRequestTransfer = $chainRouterMock->routeResource($glueRequestTransfer);

        //Assert
        $this->assertSame('fooResource', $glueRequestTransfer->getResource()->getResourceName());
        $this->assertSame('fooMethod', $glueRequestTransfer->getResource()->getMethod());
        $this->assertSame(['fooController', 'fooAction'], $glueRequestTransfer->getResource()->getContollerExecutable());
        $this->assertSame('fooAction', $glueRequestTransfer->getResource()->getAction());
        $this->assertSame($matchResult, $glueRequestTransfer->getResource()->getParameters());
    }

    /**
     * @return void
     */
    public function testRouteResourceReturnsGlueRequestTransferDoesNotContainResource(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setPath(static::HTTP_PATH)
            ->setMethod(static::HTTP_METHOD)
            ->setHost(static::HTTP_HOST);

        $routerPluginInterface = $this->createMock(RouterPluginInterface::class);

        $chainRouterMock = $this->getMockBuilder(ChainRouter::class)
            ->setConstructorArgs([[$routerPluginInterface]])
            ->setMethods(['filterParameters', 'setContext', 'match'])
            ->getMock();
        $chainRouterMock
            ->expects($this->once())
            ->method('setContext');
        $chainRouterMock
            ->expects($this->once())
            ->method('match')
            ->willThrowException($this->createMock(ExceptionInterface::class));
        $chainRouterMock
            ->expects($this->never())
            ->method('filterParameters');

        //Act
        $glueRequestTransfer = $chainRouterMock->routeResource($glueRequestTransfer);

        //Assert
        $this->assertNull($glueRequestTransfer->getResource());
    }
}
