<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GracefulRunner\Business;

use Codeception\Test\Unit;
use SprykerTest\Zed\GracefulRunner\GracefulRunnerException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GracefulRunner
 * @group Business
 * @group Facade
 * @group GracefulRunnerFacadeTest
 * Add your own group annotations below this line
 */
class GracefulRunnerFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\GracefulRunner\GracefulRunnerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRunReturnsNumberOfExecutedIterations(): void
    {
        // Arrange
        $generator = $this->tester->getGenerator();

        // Act
        $executedIterations = $this->tester->getFacade()->run($generator);

        // Assert
        $this->assertSame(3, $executedIterations, sprintf('Expected to have "%s" executed iterations but got "%s"', 3, $executedIterations));
    }

    /**
     * @return void
     */
    public function testRunWithoutSendingSignalExecutesAllIterations(): void
    {
        // Arrange
        $generator = $this->tester->getGenerator();

        // Act
        $this->tester->getFacade()->run($generator);

        // Assert
        $this->tester->assertGeneratorCalls(3, 15);
    }

    /**
     * @return void
     */
    public function testRunWithSendingSignalExecutesUntilSignalIsTriggered(): void
    {
        // Arrange
        $generator = $this->tester->getGenerator(1);

        // Act
        $this->tester->getFacade()->run($generator);

        // Assert
        $this->tester->assertGeneratorCalls(1, 5);
    }

    /**
     * @return void
     */
    public function testRunWithSendingSignalExecutesUntilSignalIsTriggeredAndReturnsResultWhenGeneratorIsWrappedWithTryCatch(): void
    {
        // Arrange
        $generator = $this->tester->getGenerator(1, true);

        // Act
        $this->tester->getFacade()->run($generator, GracefulRunnerException::class);

        // Assert
        $this->assertIsArray($generator->getReturn());
    }

    /**
     * @return void
     */
    public function testRunAndGeneratorGetReturnIsCalledThrowsExceptionWhenGeneratorIsNotWrappedWithTryCatch(): void
    {
        // Arrange
        $generator = $this->tester->getGenerator(1);

        // Expect
        $this->expectException(GracefulRunnerException::class);

        // Act
        $this->tester->getFacade()->run($generator, GracefulRunnerException::class);
    }
}
