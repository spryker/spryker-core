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
use Spryker\Glue\GlueApplication\Exception\UnknownRequestFlowImplementationException;
use Spryker\Glue\GlueApplication\Http\Request\RequestBuilderInterface;
use Spryker\Glue\GlueApplication\Http\Response\HttpSenderInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\CommunicationProtocolPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;
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
        $apiApplicationConventionMock = $this->createMock(ConventionPluginInterface::class);
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

        $requestBuilderMock = $this->createMock(RequestBuilderInterface::class);
        $requestBuilderMock
            ->expects($this->never())
            ->method('extract');

        $httpSenderMock = $this->createMock(HttpSenderInterface::class);
        $httpSenderMock
            ->expects($this->never())
            ->method('sendResponse');

        $apiApplicationProxy = new ApiApplicationProxy(
            $bootstrapPluginMock,
            $requestFlowExecutorMock,
            [$communicationProtocolPluginMock],
            [$apiApplicationConventionMock],
            $requestBuilderMock,
            $httpSenderMock,
        );
        $apiApplicationProxy->boot();
    }

    /**
     * @return void
     */
    public function testRunIsExecutedOnRequestFlowAgnosticBootstrapPlugin(): void
    {
        $apiApplicationConventionMock = $this->createMock(ConventionPluginInterface::class);
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

        $requestBuilderMock = $this->createMock(RequestBuilderInterface::class);
        $requestBuilderMock
            ->expects($this->never())
            ->method('extract');

        $httpSenderMock = $this->createMock(HttpSenderInterface::class);
        $httpSenderMock
            ->expects($this->never())
            ->method('sendResponse');

        $apiApplicationProxy = new ApiApplicationProxy(
            $bootstrapPluginMock,
            $requestFlowExecutorMock,
            [$communicationProtocolPluginMock],
            [$apiApplicationConventionMock],
            $requestBuilderMock,
            $httpSenderMock,
        );
        $apiApplicationProxy->run();
    }

    /**
     * @return void
     */
    public function testExecuteRequestIsExecutedOnRequestFlowAwareApiApplicationPluginIfCommunicationProtocolIsDefined(): void
    {
        $apiApplicationConventionMock = $this->createMock(ConventionPluginInterface::class);
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

        $requestBuilderMock = $this->createMock(RequestBuilderInterface::class);
        $requestBuilderMock
            ->expects($this->never())
            ->method('extract');

        $httpSenderMock = $this->createMock(HttpSenderInterface::class);
        $httpSenderMock
            ->expects($this->never())
            ->method('sendResponse');

        $apiApplicationProxy = new ApiApplicationProxy(
            $bootstrapPluginMock,
            $requestFlowExecutorMock,
            [$communicationProtocolPluginMock],
            [$apiApplicationConventionMock],
            $requestBuilderMock,
            $httpSenderMock,
        );
        $apiApplicationProxy->run();
    }

    /**
     * @return void
     */
    public function testExecuteRequestIsExecutedOnRequestFlowAwareApiApplicationPluginThoughDefaultHttpProtocolIfCommunicationPluginNotApplicable(): void
    {
        $apiApplicationConventionMock = $this->createMock(ConventionPluginInterface::class);

        $communicationProtocolPluginMock = $this->createMock(CommunicationProtocolPluginInterface::class);
        $communicationProtocolPluginMock
            ->expects($this->once())
            ->method('isApplicable')
            ->willReturn(false);

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

        $requestBuilderMock = $this->createMock(RequestBuilderInterface::class);
        $requestBuilderMock
            ->expects($this->once())
            ->method('extract');

        $httpSenderMock = $this->createMock(HttpSenderInterface::class);
        $httpSenderMock
            ->expects($this->once())
            ->method('sendResponse');

        $apiApplicationProxy = new ApiApplicationProxy(
            $bootstrapPluginMock,
            $requestFlowExecutorMock,
            [$communicationProtocolPluginMock],
            [$apiApplicationConventionMock],
            $requestBuilderMock,
            $httpSenderMock,
        );
        $apiApplicationProxy->run();
    }

    /**
     * @return void
     */
    public function testExecuteRequestIsExecutedOnRequestFlowAwareApiApplicationPluginThoughDefaultHttpProtocol(): void
    {
        $apiApplicationConventionMock = $this->createMock(ConventionPluginInterface::class);
        $apiApplicationConventionMock
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

        $requestBuilderMock = $this->createMock(RequestBuilderInterface::class);
        $requestBuilderMock
            ->expects($this->once())
            ->method('extract');

        $httpSenderMock = $this->createMock(HttpSenderInterface::class);
        $httpSenderMock
            ->expects($this->once())
            ->method('sendResponse');

        $apiApplicationProxy = new ApiApplicationProxy(
            $bootstrapPluginMock,
            $requestFlowExecutorMock,
            [],
            [$apiApplicationConventionMock],
            $requestBuilderMock,
            $httpSenderMock,
        );
        $apiApplicationProxy->run();
    }

    /**
     * @return void
     */
    public function testExceptionIsThrownIfNeitherRequestFlowAwareNorAgnosticIsImplemented(): void
    {
        $apiApplicationConventionMock = $this->createMock(ConventionPluginInterface::class);
        $bootstrapPluginMock = $this->createMock(GlueApplicationBootstrapPluginInterface::class);
        $requestFlowExecutorMock = $this->createMock(RequestFlowExecutorInterface::class);
        $requestBuilderMock = $this->createMock(RequestBuilderInterface::class);
        $httpSenderMock = $this->createMock(HttpSenderInterface::class);

        $this->expectException(UnknownRequestFlowImplementationException::class);

        $apiApplicationProxy = new ApiApplicationProxy(
            $bootstrapPluginMock,
            $requestFlowExecutorMock,
            [],
            [$apiApplicationConventionMock],
            $requestBuilderMock,
            $httpSenderMock,
        );
        $apiApplicationProxy->run();
    }
}
