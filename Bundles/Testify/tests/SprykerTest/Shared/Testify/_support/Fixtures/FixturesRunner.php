<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Fixtures;

use Codeception\Codecept;
use Codeception\Suite;
use ReflectionClass;

class FixturesRunner extends Codecept
{
    /**
     * @inheritDoc
     */
    public function runSuite(array $settings, string $suite, ?string $test = null): void
    {
        $settings['shard'] = $this->options['shard'];
        $suiteManager = new FixturesSuiteManager($this->dispatcher, $suite, $settings, $this->options);
        $suiteManager->initialize();

        mt_srand($this->options['seed']);
        $suiteManager->loadTests($test);
        mt_srand();

        $suiteManager->run($this->resultAggregator);
    }

    /**
     * @param \Codeception\Suite $suite
     *
     * @return bool
     */
    protected function suiteContainsFixtures(Suite $suite): bool
    {
        if ($this->getTestMethodCountForSuite($suite) === 0) {
            return false;
        }

        return true;
    }

    /**
     * @param \Codeception\Suite $suite
     *
     * @return int
     */
    protected function getTestMethodCountForSuite(Suite $suite): int
    {
        $suiteReflection = new ReflectionClass($suite);
        $suiteTestProperty = $suiteReflection->getProperty('tests');
        $suiteTestProperty->setAccessible(true);

        return count($suiteTestProperty->getValue($suite));
    }
}
