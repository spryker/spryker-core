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
    const TEST_ENV_SCRIPT = 'setup_test';

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

        $process = $this->runTestSetup('--restore');

        if (!$process->isSuccessful()) {
            throw new Exception('An error in data restore occurred: ' . $process->getErrorOutput());
        }
    }

    /**
     * @return $this
     */
    public function runCollectors()
    {
        $this->runTestSetup('--collectors');

        return $this;
    }

    /**
     * @param string $argument
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function runTestSetup($argument)
    {
        $process = new Process(sprintf(
            '%s' . self::TEST_ENV_SCRIPT . ' %s',
            $this->getSetupScriptPath(),
            $argument
        ));

        $process->run();

        return $process;
    }

    /**
     * @return string
     */
    protected function getSetupScriptPath()
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR;
    }
}
