<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GracefulRunner\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Generator;
use SprykerTest\Zed\GracefulRunner\GracefulRunnerException;

class GracefulRunnerHelper extends Module
{
    /**
     * @var int
     */
    protected $numberOfExecutedIterations = 0;

    /**
     * @var int
     */
    protected $numberOfExecutedItems = 0;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->numberOfExecutedIterations = 0;
        $this->numberOfExecutedItems = 0;
    }

    /**
     * @param int|null $breakAfter
     * @param bool $catch
     *
     * @return \Generator
     */
    public function getGenerator(?int $breakAfter = null, bool $catch = false): Generator
    {
        $result = [];

        if (!$catch) {
            foreach ([1, 2, 3] as $iteration) {
                yield;

                if ($breakAfter && $iteration === $breakAfter) {
                    posix_kill(posix_getpid(), SIGINT);
                    posix_kill(posix_getpid(), SIGTERM);
                    pcntl_signal_dispatch();
                }

                $this->executeOneIteration();

                $result[] = sprintf('Executed run: %d', $iteration);
            }

            return $result;
        }

        try {
            foreach ([1, 2, 3] as $iteration) {
                yield;

                if ($breakAfter && $iteration === $breakAfter) {
                    posix_kill(posix_getpid(), SIGINT);
                    posix_kill(posix_getpid(), SIGTERM);
                    pcntl_signal_dispatch();
                }

                $this->executeOneIteration();

                $result[] = sprintf('Executed run: %d', $iteration);
            }
        } catch (GracefulRunnerException $throwable) {
        }

        return $result;
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

    /**
     * @param int $expectedNumberOfExecutedIterations
     * @param int $expectedNumberOfExecutedItems
     *
     * @return void
     */
    public function assertGeneratorCalls(int $expectedNumberOfExecutedIterations, int $expectedNumberOfExecutedItems): void
    {
        $this->assertSame(
            $expectedNumberOfExecutedIterations,
            $this->numberOfExecutedIterations,
            sprintf('Expected to have "%s" executed iterations but got "%s"', $expectedNumberOfExecutedIterations, $this->numberOfExecutedItems)
        );

        $this->assertSame(
            $expectedNumberOfExecutedItems,
            $this->numberOfExecutedItems,
            sprintf('Expected to have "%s" executed items but got "%s"', $expectedNumberOfExecutedItems, $this->numberOfExecutedItems)
        );
    }
}
