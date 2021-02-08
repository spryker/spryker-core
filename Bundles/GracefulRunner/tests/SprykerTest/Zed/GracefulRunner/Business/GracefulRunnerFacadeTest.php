<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GracefulRunner\Business;

use Codeception\Test\Unit;
use Generator;

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
     * @var int
     */
    protected $numberOfExecutedIterations = 0;

    /**
     * @var int
     */
    protected $numberOfExecutedItems = 0;

    /**
     * @return void
     */
    public function testRunReturnsNumberOfExecutedIterations(): void
    {
        $executedIterations = $this->tester->getFacade()->run($this->getGenerator());

        $this->assertSame(3, $executedIterations, sprintf('Expected to have "%s" executed iterations but got "%s"', 3, $executedIterations));
    }

    /**
     * @return void
     */
    public function testRunWithoutSendingSignalExecutesAllIterations(): void
    {
        $this->tester->getFacade()->run($this->getGenerator());

        $this->assertSame(3, $this->numberOfExecutedIterations, sprintf('Expected to have "%s" executed iterations but got "%s"', 3, $this->numberOfExecutedItems));
        $this->assertSame(15, $this->numberOfExecutedItems, sprintf('Expected to have "%s" executed items but got "%s"', 15, $this->numberOfExecutedItems));
    }

    /**
     * @return void
     */
    public function testRunWithSendingSignalExecutesUntilSignalIsTriggered(): void
    {
        $this->tester->getFacade()->run($this->getGenerator(1));

        $this->assertSame(1, $this->numberOfExecutedIterations, sprintf('Expected to have "%s" executed iterations but got "%s"', 1, $this->numberOfExecutedItems));
        $this->assertSame(5, $this->numberOfExecutedItems, sprintf('Expected to have "%s" executed items but got "%s"', 5, $this->numberOfExecutedItems));
    }

    /**
     * @param int|null $breakAfter
     *
     * @return \Generator
     */
    protected function getGenerator(?int $breakAfter = null): Generator
    {
        foreach ([1, 2, 3] as $iteration) {
            yield;

            if ($breakAfter && $iteration === $breakAfter) {
                posix_kill(posix_getpid(), SIGINT);
                posix_kill(posix_getpid(), SIGTERM);
                pcntl_signal_dispatch();
            }

            $this->executeOneIteration();
        }
    }

    /**
     * @return void
     */
    protected function executeOneIteration(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->numberOfExecutedItems++;
        }

        $this->numberOfExecutedIterations++;
    }
}
