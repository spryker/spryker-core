<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Exception;
use Symfony\Component\Process\Process;

class SetupHelper extends Module
{
    const TEST_ENV_SCRIPT = 'php spryker.phar setup testing';

    /**
     * @param \Codeception\TestInterface $test
     *
     * @throws \Exception
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);

        $process = $this->runTestSetup('-s restore');

        if (!$process->isSuccessful()) {
            throw new Exception('An error in data restore occurred: ' . $process->getErrorOutput());
        }
    }

    /**
     * @return $this
     */
    public function runCollectors()
    {
        $this->runTestSetup('-s export-data');

        return $this;
    }

    /**
     * @param string $argument
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function runTestSetup($argument)
    {
        $command = sprintf(static::TEST_ENV_SCRIPT . ' %s', $argument);
        $process = new Process($command, APPLICATION_ROOT_DIR);

        $process->run();

        return $process;
    }
}
