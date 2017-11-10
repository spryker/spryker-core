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
    const SPRYKER_SETUP = 'vendor/bin/deploy testing';

    /**
     * @deprecated Please use the new spryker deploy tool.
     */
    const TEST_ENV_SCRIPT = 'setup_test';

    /**
     * @var bool
     */
    protected $hasSetupTool;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);

        if ($this->hasSprykerSetup()) {
            $this->run('-s restore');

            return;
        }

        $this->run('--restore');
    }

    /**
     * @return bool
     */
    protected function hasSprykerSetup()
    {
        if ($this->hasSetupTool === null) {
            $this->hasSetupTool = file_exists(APPLICATION_ROOT_DIR . '/spryker.phar');
        }

        return $this->hasSetupTool;
    }

    /**
     * @return $this
     */
    public function runCollectors()
    {
        if ($this->hasSprykerSetup()) {
            $this->run('-s export-data');

            return $this;
        }

        $this->run('--collectors');

        return $this;
    }

    /**
     * @param string $argument
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function run($argument)
    {
        $command = $this->buildCommandToExecute($argument);
        $process = new Process($command, APPLICATION_ROOT_DIR);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new Exception('An error in data restore occurred: ' . $process->getErrorOutput());
        }
    }

    /**
     * @param string $argument
     *
     * @return string
     */
    protected function buildCommandToExecute($argument)
    {
        if ($this->hasSprykerSetup()) {
            return sprintf(static::SPRYKER_SETUP . ' %s', $argument);
        }

        return sprintf(static::TEST_ENV_SCRIPT . ' %s', $argument);
    }
}
