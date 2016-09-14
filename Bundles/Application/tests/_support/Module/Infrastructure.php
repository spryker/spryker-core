<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Application\Module;

use Codeception\Module;
use Codeception\TestCase;
use Symfony\Component\Process\Process;

class Infrastructure extends Module
{
    const TEST_ENV_SCRIPT = 'setup_test.sh';

    /**
     * @param \Codeception\TestCase $test
     * @throws \Exception
     *
     * @return void
     */
    public function _before(TestCase $test)
    {
        parent::_before($test);

        $process = $this->runTestSetup('--restore');
        $process->run();
        if ($process->getExitCode() != 0) {
            throw new \Exception('An error in data restore occured: '. $process->getErrorOutput());
        }
    }

    /**
     * @return $this
     */
    public function runCollectors()
    {
        $process = $this->runTestSetup('--collectors');
        $process->run();

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
            APPLICATION_ROOT_DIR,
            $argument
        ));
        return $process;
    }
}
