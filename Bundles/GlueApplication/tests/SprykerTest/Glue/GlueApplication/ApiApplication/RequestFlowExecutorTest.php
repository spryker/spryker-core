<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\ApiApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\ApiApplication\RequestFlowExecutor;
use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication;
use Spryker\Glue\GlueApplication\Executor\ResourceExecutorInterface;
use Spryker\Glue\GlueApplication\Router\RouteMatcherInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group ApiApplication
 * @group RequestFlowExecutorTest
 * Add your own group annotations below this line
 */
class RequestFlowExecutorTest extends Unit
{
    /**
     * @return void
     */
    public function testRequestFlowIsExecutedOnRequestFlowAwareApiApplicationPlugin(): void
    {
        $apiConventionPluginMock = $this->createBaseApiConventionPluginMock($this->createResourceMock());
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('provideRequestAfterRoutingValidatorPlugins')
            ->willReturn([]);

        $apiApplicationPluginMock = $this->createBaseApiApplicationPluginMock($this->createResourceMock());
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideRequestAfterRoutingValidatorPlugins')
            ->willReturn([]);

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->once())
            ->method('executeResource')
            ->willReturn(new GlueResponseTransfer());

        $routeMatcherMock = $this->createMock(RouteMatcherInterface::class);
        $routeMatcherMock
            ->expects($this->once())
            ->method('route')
            ->willReturn($this->createMock(ResourceInterface::class));

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock, $routeMatcherMock);
        $requestFlowExecutor->executeRequestFlow(
            new GlueRequestTransfer(),
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
        );
    }

    /**
     * @return void
     */
    public function testRequestFlowSendsResponseOnConventionValidationError(): void
    {
        $expectedValidationResult = (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setValidationError('failed validation');

        $requestValidatorPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $requestValidatorPluginMock->method('validate')
            ->willReturn($expectedValidationResult);

        $anotherRequestValidatorPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $anotherRequestValidatorPluginMock->expects($this->never())
            ->method('validate');

        $apiConventionPluginMock = $this->createMock(ConventionPluginInterface::class);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('provideRequestBuilderPlugins')
            ->willReturn([]);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('provideRequestValidatorPlugins')
            ->willReturn([$requestValidatorPluginMock]);

        $apiApplicationPluginMock = $this->createMock(RequestFlowAwareApiApplication::class);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideRequestBuilderPlugins')
            ->willReturn([]);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideRequestValidatorPlugins')
            ->willReturn([$anotherRequestValidatorPluginMock]);

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->never())
            ->method('executeResource');

        $routeMatcherMock = $this->createMock(RouteMatcherInterface::class);
        $routeMatcherMock
            ->expects($this->never())
            ->method('route')
            ->willReturn($this->createMock(ResourceInterface::class));

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock, $routeMatcherMock);
        $requestFlowExecutor->executeRequestFlow(
            new GlueRequestTransfer(),
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
        );
    }

    /**
     * @return void
     */
    public function testRequestFlowSendsResponseOnApplicationValidationError(): void
    {
        $requestValidatorPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $requestValidatorPluginMock->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $expectedValidationResult = (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setValidationError('failed validation');

        $anotherRequestValidatorPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $anotherRequestValidatorPluginMock->method('validate')
            ->willReturn($expectedValidationResult);

        $apiConventionPluginMock = $this->createMock(ConventionPluginInterface::class);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('provideRequestBuilderPlugins')
            ->willReturn([]);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('provideRequestValidatorPlugins')
            ->willReturn([$requestValidatorPluginMock]);

        $apiApplicationPluginMock = $this->createMock(RequestFlowAwareApiApplication::class);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideRequestBuilderPlugins')
            ->willReturn([]);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideRequestValidatorPlugins')
            ->willReturn([$anotherRequestValidatorPluginMock]);

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->never())
            ->method('executeResource');

        $routeMatcherMock = $this->createMock(RouteMatcherInterface::class);
        $routeMatcherMock
            ->expects($this->never())
            ->method('route')
            ->willReturn($this->createMock(ResourceInterface::class));

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock, $routeMatcherMock);
        $requestFlowExecutor->executeRequestFlow(
            new GlueRequestTransfer(),
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
        );
    }

    /**
     * @return void
     */
    public function testRequestFlowSendsResponseOnRoutingError(): void
    {
        $this->executeRequestFlowExecutorWithRoutingError(
            $this->createMock(MissingResourceInterface::class),
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface $expectedResource
     *
     * @return void
     */
    protected function executeRequestFlowExecutorWithRoutingError(MissingResourceInterface $expectedResource): void
    {
        $apiConventionPluginMock = $this->createBaseApiConventionPluginMock($this->createResourceMock());

        $requestValidatorPluginMock = $this->createMock(RequestValidatorPluginInterface::class);
        $requestValidatorPluginMock->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $apiApplicationPluginMock = $this->createMock(RequestFlowAwareApiApplication::class);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideRequestBuilderPlugins')
            ->willReturn([]);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideRequestValidatorPlugins')
            ->willReturn([$requestValidatorPluginMock]);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideResponseFormatterPlugins')
            ->willReturn([]);

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->once())
            ->method('executeResource')
            ->willReturn(new GlueResponseTransfer());

        $routeMatcherMock = $this->createMock(RouteMatcherInterface::class);
        $routeMatcherMock
            ->expects($this->once())
            ->method('route')
            ->willReturn($this->createMock(MissingResourceInterface::class));

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock, $routeMatcherMock);
        $requestFlowExecutor->executeRequestFlow(
            new GlueRequestTransfer(),
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
        );
    }

    /**
     * @return void
     */
    public function testRequestFlowSendsResponseOnConventionValidationErrorAfterRouting(): void
    {
        $expectedValidationResult = (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setValidationError('failed validation');

        $requestAfterRoutingValidatorPluginMock = $this->createMock(RequestAfterRoutingValidatorPluginInterface::class);
        $requestAfterRoutingValidatorPluginMock->method('validate')
            ->willReturn($expectedValidationResult);

        $anotherRequestAfterRoutingValidatorPluginMock = $this->createMock(RequestAfterRoutingValidatorPluginInterface::class);
        $anotherRequestAfterRoutingValidatorPluginMock->expects($this->never())
            ->method('validate');

        $apiConventionPluginMock = $this->createBaseApiConventionPluginMock($this->createResourceMock());
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('provideRequestAfterRoutingValidatorPlugins')
            ->willReturn([$requestAfterRoutingValidatorPluginMock]);

        $apiApplicationPluginMock = $this->createBaseApiApplicationPluginMock($this->createResourceMock());
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideRequestAfterRoutingValidatorPlugins')
            ->willReturn([$anotherRequestAfterRoutingValidatorPluginMock]);

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->never())
            ->method('executeResource');

        $routeMatcherMock = $this->createMock(RouteMatcherInterface::class);
        $routeMatcherMock
            ->expects($this->once())
            ->method('route')
            ->willReturn($this->createMock(ResourceInterface::class));

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock, $routeMatcherMock);
        $requestFlowExecutor->executeRequestFlow(
            new GlueRequestTransfer(),
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
        );
    }

    /**
     * @return void
     */
    public function testRequestFlowSendsResponseOnApplicationValidationErrorAfterRouting(): void
    {
        $requestAfterRoutingValidatorPluginMock = $this->createMock(RequestAfterRoutingValidatorPluginInterface::class);
        $requestAfterRoutingValidatorPluginMock->method('validate')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $expectedValidationResult = (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setValidationError('failed validation');

        $anotherRequestAfterRoutingValidatorPluginMock = $this->createMock(RequestAfterRoutingValidatorPluginInterface::class);
        $anotherRequestAfterRoutingValidatorPluginMock->method('validate')
            ->willReturn($expectedValidationResult);

        $apiConventionPluginMock = $this->createBaseApiConventionPluginMock($this->createResourceMock());
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('provideRequestAfterRoutingValidatorPlugins')
            ->willReturn([$requestAfterRoutingValidatorPluginMock]);

        $routeMatcherMock = $this->createMock(RouteMatcherInterface::class);
        $routeMatcherMock
            ->expects($this->once())
            ->method('route')
            ->willReturn($this->createMock(ResourceInterface::class));

        $apiApplicationPluginMock = $this->createBaseApiApplicationPluginMock($this->createResourceMock());
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideRequestAfterRoutingValidatorPlugins')
            ->willReturn([$anotherRequestAfterRoutingValidatorPluginMock]);

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->never())
            ->method('executeResource');

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock, $routeMatcherMock);
        $requestFlowExecutor->executeRequestFlow(
            new GlueRequestTransfer(),
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    protected function createResourceMock(): ResourceInterface
    {
        $resourceMock = $this->createMock(ResourceInterface::class);
        $resourceMock->expects($this->never())
            ->method('getResource')
            ->willReturn(function (): void {
            });

        return $resourceMock;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resourceMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAwareApiApplication
     */
    protected function createBaseApiApplicationPluginMock(ResourceInterface $resourceMock): RequestFlowAwareApiApplication
    {
        $apiApplicationPluginMock = $this->createMock(RequestFlowAwareApiApplication::class);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideRequestBuilderPlugins')
            ->willReturn([]);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideRequestValidatorPlugins')
            ->willReturn([]);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('provideResponseFormatterPlugins')
            ->willReturn([]);

        return $apiApplicationPluginMock;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resourceMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface
     */
    protected function createBaseApiConventionPluginMock(ResourceInterface $resourceMock): ConventionPluginInterface
    {
        $apiConventionPluginMock = $this->createMock(ConventionPluginInterface::class);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('provideRequestBuilderPlugins')
            ->willReturn([]);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('provideRequestValidatorPlugins')
            ->willReturn([]);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('provideResponseFormatterPlugins')
            ->willReturn([]);

        return $apiConventionPluginMock;
    }
}
