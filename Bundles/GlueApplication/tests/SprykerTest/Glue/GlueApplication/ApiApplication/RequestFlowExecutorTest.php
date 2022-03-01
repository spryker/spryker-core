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
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

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
            ->method('validateRequestAfterRouting')
            ->willReturn(
                (new GlueRequestValidationTransfer())
                    ->setIsValid(true),
            );

        $apiApplicationPluginMock = $this->createBaseApiApplicationPluginMock($this->createResourceMock());
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('validateRequestAfterRouting')
            ->willReturn(
                (new GlueRequestValidationTransfer())
                    ->setIsValid(true),
            );

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->once())
            ->method('executeResource')
            ->willReturn(new GlueResponseTransfer());

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock);
        $requestFlowExecutor->executeRequestFlow(
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
            new GlueRequestTransfer(),
        );
    }

    /**
     * @return void
     */
    public function testRequestFlowSendsResponseOnConventionValidationError(): void
    {
        $expectedValidationResult = (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatusCode('400')
            ->setValidationError('failed validation');

        $apiConventionPluginMock = $this->createMock(ApiConventionPluginInterface::class);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('buildRequest')
            ->willReturnArgument(0);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('validateRequest')
            ->willReturn($expectedValidationResult);

        $apiApplicationPluginMock = $this->createMock(RequestFlowAwareApiApplication::class);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('buildRequest')
            ->willReturnArgument(0);
        $apiApplicationPluginMock
            ->expects($this->never())
            ->method('validateRequest');

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->never())
            ->method('executeResource');

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock);
        $requestFlowExecutor->executeRequestFlow(
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
            new GlueRequestTransfer(),
        );
    }

    /**
     * @return void
     */
    public function testRequestFlowSendsResponseOnApplicationValidationError(): void
    {
        $expectedValidationResult = (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatusCode('400')
            ->setValidationError('failed validation');

        $apiConventionPluginMock = $this->createMock(ApiConventionPluginInterface::class);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('buildRequest')
            ->willReturnArgument(0);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('validateRequest')
            ->willReturn(
                (new GlueRequestValidationTransfer())
                ->setIsValid(true),
            );

        $apiApplicationPluginMock = $this->createMock(RequestFlowAwareApiApplication::class);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('buildRequest')
            ->willReturnArgument(0);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('validateRequest')
            ->willReturn($expectedValidationResult);

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->never())
            ->method('executeResource');

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock);
        $requestFlowExecutor->executeRequestFlow(
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
            new GlueRequestTransfer(),
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

        $apiApplicationPluginMock = $this->createMock(RequestFlowAwareApiApplication::class);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('buildRequest')
            ->willReturnArgument(0);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('validateRequest')
            ->willReturn(
                (new GlueRequestValidationTransfer())
                    ->setIsValid(true),
            );
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('route')
            ->willReturn($expectedResource);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('formatResponse')
            ->willReturnArgument(0);

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->once())
            ->method('executeResource')
            ->willReturn(new GlueResponseTransfer());

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock);
        $requestFlowExecutor->executeRequestFlow(
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
            new GlueRequestTransfer(),
        );
    }

    /**
     * @return void
     */
    public function testRequestFlowSendsResponseOnConventionValidationErrorAfterRouting(): void
    {
        $expectedValidationResult = (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatusCode('400')
            ->setValidationError('failed validation');

        $apiConventionPluginMock = $this->createBaseApiConventionPluginMock($this->createResourceMock());
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('validateRequestAfterRouting')
            ->willReturn($expectedValidationResult);

        $apiApplicationPluginMock = $this->createBaseApiApplicationPluginMock($this->createResourceMock());
        $apiApplicationPluginMock
            ->expects($this->never())
            ->method('validateRequestAfterRouting');

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->never())
            ->method('executeResource');

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock);
        $requestFlowExecutor->executeRequestFlow(
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
            new GlueRequestTransfer(),
        );
    }

    /**
     * @return void
     */
    public function testRequestFlowSendsResponseOnApplicationValidationErrorAfterRouting(): void
    {
        $expectedValidationResult = (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatusCode('400')
            ->setValidationError('failed validation');

        $apiConventionPluginMock = $this->createBaseApiConventionPluginMock($this->createResourceMock());
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('validateRequestAfterRouting')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));

        $apiApplicationPluginMock = $this->createBaseApiApplicationPluginMock($this->createResourceMock());
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('validateRequestAfterRouting')
            ->willReturn($expectedValidationResult);

        $resourceExecutorMock = $this->createMock(ResourceExecutorInterface::class);
        $resourceExecutorMock->expects($this->never())
            ->method('executeResource');

        $requestFlowExecutor = new RequestFlowExecutor($resourceExecutorMock);
        $requestFlowExecutor->executeRequestFlow(
            $apiApplicationPluginMock,
            $apiConventionPluginMock,
            new GlueRequestTransfer(),
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
            ->method('buildRequest')
            ->willReturnArgument(0);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('validateRequest')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('route')
            ->willReturn($resourceMock);
        $apiApplicationPluginMock
            ->expects($this->once())
            ->method('formatResponse')
            ->willReturnArgument(0);

        return $apiApplicationPluginMock;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resourceMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiConventionPluginInterface
     */
    protected function createBaseApiConventionPluginMock(ResourceInterface $resourceMock): ApiConventionPluginInterface
    {
        $apiConventionPluginMock = $this->createMock(ApiConventionPluginInterface::class);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('buildRequest')
            ->willReturnArgument(0);
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('validateRequest')
            ->willReturn((new GlueRequestValidationTransfer())->setIsValid(true));
        $apiConventionPluginMock
            ->expects($this->once())
            ->method('formatResponse')
            ->willReturnArgument(0);

        return $apiConventionPluginMock;
    }
}
