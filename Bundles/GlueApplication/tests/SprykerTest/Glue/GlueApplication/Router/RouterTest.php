<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Router;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Router;
use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\RouterInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Router
 * @group RouterTest
 * Add your own group annotations below this line
 */
class RouterTest extends Unit
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
    protected const HTTP_HOST = 'glue-storefront.de.spryker.local';

    /**
     * @return void
     */
    public function testRouteResourceReturnsGlueRequestTransferExpandedWithResource(): void
    {
        //Arrange
        $matchResult = [
            '_method' => 'fooMethod',
            '_controller' => ['fooController', 'fooAction'],
            '_action' => 'fooAction',
        ];

        $routerMock = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['match'])
            ->getMock();
        $routerMock
            ->expects($this->once())
            ->method('match')
            ->willReturn($matchResult);

        $glueRequestTransfer = (new GlueRequestTransfer())
            ->setPath(static::HTTP_PATH)
            ->setMethod(static::HTTP_METHOD)
            ->setHost(static::HTTP_HOST);

        //Act
        $glueRequestTransfer = $routerMock->routeRequest($glueRequestTransfer);

        //Assert
        $this->assertSame('fooMethod', $glueRequestTransfer->getResource()->getMethod());
        $this->assertSame(['fooController', 'fooAction'], $glueRequestTransfer->getResource()->getControllerExecutable());
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

        $routerInterface = $this->createMock(RouterInterface::class);

        //Act
        $glueRequestTransfer = $routerInterface->routeRequest($glueRequestTransfer);

        //Assert
        $this->assertNull($glueRequestTransfer->getResource());
    }
}
