<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\ApiApplication;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\ApiApplication\ApiApplicationProxy;
use Spryker\Glue\GlueApplication\ApiApplication\RequestFlowExecutorInterface;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAgnosticApiApplication;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication;
use Spryker\Glue\GlueApplication\Exception\MissingCommunicationProtocolException;
use Spryker\Glue\GlueApplication\Exception\UnknownRequestFlowImplementationException;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;
use Spryker\Shared\Application\ApplicationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group ApiApplication
 * @group ApiApplicationProxyTest
 * Add your own group annotations below this line
 */
class ApiApplicationProxyTest extends Unit
{
    /**
     * @return void
     */
    public function testBootIsExecutedOnBootBootstrapPlugin(): void
    {
        $apiApplicationConventionMock = $this->createMock(ApiConventionPluginInterface::class);
        $communicationProtocolPluginMock = $this->createMock(CommunicationProtocolPluginInterface::class);

        $requestFlowExecutorMock = $this->createMock(RequestFlowExecutorInterface::class);
        $applicationMock = $this->createMock(ApplicationInterface::class);
        $applicationMock
            ->expects($this->once())
            ->method('boot');

        $bootstrapPluginMock = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $bootstrapPluginMock
            ->expects($this->once())
            ->method('getApplication')
            ->willReturn($applicationMock);

        $apiApplicationProxy = new ApiApplicationProxy(
            $bootstrapPluginMock,
            $requestFlowExecutorMock,
            [$communicationProtocolPluginMock],
            [$apiApplicationConventionMock],
        );
        $apiApplicationProxy->boot();
    }

    /**
     * @return void
     */
    public function testRunIsExecutedOnRequestFlowAgnosticBootstrapPlugin(): void
    {
        $apiApplicationConventionMock = $this->createMock(ApiConventionPluginInterface::class);
        $communicationProtocolPluginMock = $this->createMock(CommunicationProtocolPluginInterface::class);
        $requestFlowExecutorMock = $this->createMock(RequestFlowExecutorInterface::class);
        $requestFlowExecutorMock
            ->expects($this->never())
            ->method('executeRequestFlow');

        $applicationMock = $this->createMock(RequestFlowAgnosticApiApplication::class);
        $applicationMock
            ->expects($this->once())
            ->method('run');

        $bootstrapPluginMock = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $bootstrapPluginMock
            ->expects($this->once())
            ->method('getApplication')
            ->willReturn($applicationMock);

        $apiApplicationProxy = new ApiApplicationProxy(
            $bootstrapPluginMock,
            $requestFlowExecutorMock,
            [$communicationProtocolPluginMock],
            [$apiApplicationConventionMock],
        );
        $apiApplicationProxy->run();
    }

    /**
     * @return void
     */
    public function testExecuteRequestIsExecutedOnRequestFlowAwareApiApplicationPlugin(): void
    {
        $apiApplicationConventionMock = $this->createMock(ApiConventionPluginInterface::class);
        $apiApplicationConventionMock
            ->expects($this->once())
            ->method('isApplicable')
            ->willReturn(true);

        $communicationProtocolPluginMock = $this->createMock(CommunicationProtocolPluginInterface::class);
        $communicationProtocolPluginMock
            ->expects($this->once())
            ->method('isApplicable')
            ->willReturn(true);

        $requestFlowExecutorMock = $this->createMock(RequestFlowExecutorInterface::class);
        $requestFlowExecutorMock
            ->expects($this->once())
            ->method('executeRequestFlow');

        $applicationMock = $this->createMock(RequestFlowAwareApiApplication::class);
        $applicationMock
            ->expects($this->never())
            ->method('run');

        $bootstrapPluginMock = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $bootstrapPluginMock
            ->expects($this->once())
            ->method('getApplication')
            ->willReturn($applicationMock);

        $apiApplicationProxy = new ApiApplicationProxy(
            $bootstrapPluginMock,
            $requestFlowExecutorMock,
            [$communicationProtocolPluginMock],
            [$apiApplicationConventionMock],
        );
        $apiApplicationProxy->run();
    }

    /**
     * @return void
     */
    public function testExceptionIsThrownIfCommunicationPluginNotApplicable(): void
    {
        $apiApplicationConventionMock = $this->createMock(ApiConventionPluginInterface::class);

        $communicationProtocolPluginMock = $this->createMock(CommunicationProtocolPluginInterface::class);
        $communicationProtocolPluginMock
            ->expects($this->once())
            ->method('isApplicable')
            ->willReturn(false);

        $requestFlowExecutorMock = $this->createMock(RequestFlowExecutorInterface::class);
        $requestFlowExecutorMock
            ->expects($this->never())
            ->method('executeRequestFlow');

        $applicationMock = $this->createMock(RequestFlowAwareApiApplication::class);
        $applicationMock
            ->expects($this->never())
            ->method('run');

        $bootstrapPluginMock = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $bootstrapPluginMock
            ->expects($this->once())
            ->method('getApplication')
            ->willReturn($applicationMock);

        $this->expectException(MissingCommunicationProtocolException::class);
        $this->expectExceptionMessage(sprintf(
            'No communication protocol that implements `%s` was found for the current request.
                Please implement one and inject into `GlueApplicationDependencyProvider::getCommunicationProtocolPlugins()`',
            CommunicationProtocolPluginInterface::class,
        ));

        $apiApplicationProxy = new ApiApplicationProxy(
            $bootstrapPluginMock,
            $requestFlowExecutorMock,
            [$communicationProtocolPluginMock],
            [$apiApplicationConventionMock],
        );
        $apiApplicationProxy->run();
    }

    /**
     * @return void
     */
    public function testExceptionIsThrownIfNeitherRequestFlowAwareNorAgnosticIsImplemented(): void
    {
        $apiApplicationConventionMock = $this->createMock(ApiConventionPluginInterface::class);
        $communicationProtocolPluginMock = $this->createMock(CommunicationProtocolPluginInterface::class);
        $bootstrapPluginMock = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $requestFlowExecutorMock = $this->createMock(RequestFlowExecutorInterface::class);

        $this->expectException(UnknownRequestFlowImplementationException::class);

        $apiApplicationProxy = new ApiApplicationProxy(
            $bootstrapPluginMock,
            $requestFlowExecutorMock,
            [$communicationProtocolPluginMock],
            [$apiApplicationConventionMock],
        );
        $apiApplicationProxy->run();
    }
}
