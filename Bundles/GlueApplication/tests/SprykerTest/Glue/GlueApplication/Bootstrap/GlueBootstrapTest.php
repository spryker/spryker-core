<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Bootstrap;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueApiContextTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationBootstrapResolverInterface;
use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationProxy;
use Spryker\Glue\GlueApplication\Bootstrap\GlueBootstrap;
use Spryker\Glue\GlueApplication\GlueApplicationFactory;
use Spryker\Glue\GlueApplication\Http\Context\ContextHttpExpanderInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueContextExpanderPluginInterface;
use Spryker\Shared\Application\ApplicationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Bootstrap
 * @group GlueBootstrapTest
 * Add your own group annotations below this line
 */
class GlueBootstrapTest extends Unit
{
    /**
     * @return void
     */
    public function testEmptyApiContextWillBeAssembled(): void
    {
        $apiContextExpanderPluginMock = $this->createMock(GlueContextExpanderPluginInterface::class);
        $apiContextExpanderPluginMock->expects($this->once())
            ->method('expand')
            ->willReturnCallback(function ($apiContextTransfer) {
                $this->assertInstanceOf(GlueApiContextTransfer::class, $apiContextTransfer);

                return $apiContextTransfer;
            });
        $glueBootstrap = $this->createGlueBootstrap(
            null,
            null,
            [$apiContextExpanderPluginMock],
        );

        $this->assertInstanceOf(ApplicationInterface::class, $glueBootstrap->boot());
    }

    /**
     * @return void
     */
    public function testEmptyApiContextWillBeAssembledByDefaultIfContextExpanderIsNotDefined(): void
    {
        $glueBootstrap = $this->createGlueBootstrap(
            null,
            null,
            [],
        );

        $this->assertInstanceOf(ApplicationInterface::class, $glueBootstrap->boot());
    }

    /**
     * @return void
     */
    public function testProvidedApiPluginsWillBePrefered(): void
    {
        $apiApplicationMock = $this->createMock(ApplicationInterface::class);
        $expectedApiApplicationPlugin = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $apiApplicationProxyMock = $this->createMock(ApiApplicationProxy::class);
        $apiApplicationProxyMock->expects($this->once())
            ->method('boot')
            ->willReturn($apiApplicationMock);
        $apiApplicationResolverMock = $this->createMock(ApiApplicationBootstrapResolverInterface::class);
        $apiApplicationResolverMock
            ->expects($this->once())
            ->method('resolveApiApplicationBootstrap')
            ->willReturnCallback(function () use ($expectedApiApplicationPlugin): GlueApplicationBootstrapPluginInterface {
                return $expectedApiApplicationPlugin;
            });

        $factoryMock = $this->createMock(GlueApplicationFactory::class);
        $factoryMock->expects($this->any())
            ->method('getGlueContextExpanderPlugins')
            ->willReturn([]);
        $factoryMock->expects($this->once())
            ->method('createApiApplicationBootstrapResolver')
            ->willReturnCallback(function (array $apiApplicationPlugins) use ($apiApplicationResolverMock, $expectedApiApplicationPlugin): ApiApplicationBootstrapResolverInterface {
                $this->assertCount(1, $apiApplicationPlugins);
                $this->assertSame($expectedApiApplicationPlugin, $apiApplicationPlugins[0]);

                return $apiApplicationResolverMock;
            });
        $factoryMock->expects($this->once())
            ->method('createApiApplicationProxy')
            ->willReturn($apiApplicationProxyMock);

        $glueBootstrap = new GlueBootstrap();
        $glueBootstrap->setFactory($factoryMock);
        $glueBootstrap->boot([$expectedApiApplicationPlugin]);
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|null $apiApplicationResolverMock
     * @param \PHPUnit\Framework\MockObject\MockObject|null $apiApplicationPluginMock
     * @param array<\PHPUnit\Framework\MockObject\MockObject> $apiContextExpanderPluginMocks
     *
     * @return \Spryker\Glue\GlueApplication\Bootstrap\GlueBootstrap
     */
    protected function createGlueBootstrap(
        ?MockObject $apiApplicationResolverMock = null,
        ?MockObject $apiApplicationPluginMock = null,
        array $apiContextExpanderPluginMocks = []
    ): GlueBootstrap {
        $apiApplicationMock = $this->createMock(ApplicationInterface::class);
        $apiApplicationProxyMock = $this->createMock(ApiApplicationProxy::class);
        $apiApplicationProxyMock->expects($this->once())
            ->method('boot')
            ->willReturn($apiApplicationMock);
        if ($apiApplicationResolverMock === null) {
            if ($apiApplicationPluginMock === null) {
                $apiApplicationPluginMock = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
            }

            $apiApplicationResolverMock = $this->createMock(ApiApplicationBootstrapResolverInterface::class);
            $apiApplicationResolverMock
                ->expects($this->once())
                ->method('resolveApiApplicationBootstrap')
                ->willReturn($apiApplicationPluginMock);
        }

        $contextHttpExpanderMock = $this->createMock(ContextHttpExpanderInterface::class);
        $contextHttpExpanderMock
            ->expects(count($apiContextExpanderPluginMocks) > 0 ? $this->never() : $this->once())
            ->method('expand')
            ->willReturnCallback(function ($apiContextTransfer) {
                $this->assertInstanceOf(GlueApiContextTransfer::class, $apiContextTransfer);

                return $apiContextTransfer;
            });

        $factoryMock = $this->createMock(GlueApplicationFactory::class);
        $factoryMock->expects($this->once())
            ->method('getGlueContextExpanderPlugins')
            ->willReturn($apiContextExpanderPluginMocks);
        $factoryMock->expects(count($apiContextExpanderPluginMocks) > 0 ? $this->never() : $this->once())
            ->method('createContextHttpExpander')
            ->willReturn(
                $contextHttpExpanderMock,
            );
        $factoryMock->expects($this->once())
            ->method('createApiApplicationBootstrapResolver')
            ->willReturn($apiApplicationResolverMock);
        $factoryMock->expects($this->once())
            ->method('createApiApplicationProxy')
            ->willReturn($apiApplicationProxyMock);

        $glueBootstrap = new GlueBootstrap();
        $glueBootstrap->setFactory($factoryMock);

        return $glueBootstrap;
    }
}
