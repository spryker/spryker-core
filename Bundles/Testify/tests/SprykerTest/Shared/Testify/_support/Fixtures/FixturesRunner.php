<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Fixtures;

use Codeception\Codecept;

class FixturesRunner extends Codecept
{
    /**
     * @inheritDoc
     */
    public function runSuite($settings, $suite, $test = null)
    {
        $suiteManager = new FixturesSuiteManager($this->dispatcher, $suite, $settings);
        $suiteManager->initialize();
        mt_srand($this->options['seed']);
        $suiteManager->loadTests($test);
        mt_srand();
        $suiteManager->run($this->runner, $this->result, $this->options);

        return $this->result;
    }
}
