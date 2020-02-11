<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Fixtures;

use Codeception\Suite;
use Codeception\SuiteManager;

class FixturesSuiteManager extends SuiteManager
{
    /**
     * @inheritDoc
     */
    public function loadTests($path = null)
    {
        $testLoader = new FixturesLoader($this->settings);
        $testLoader->loadTests($path);

        $tests = $testLoader->getTests();

        foreach ($tests as $test) {
            $this->addToSuite($test);
        }

        if ($this->suite instanceof Suite) {
            $this->suite->reorderDependencies();
        }
    }
}
