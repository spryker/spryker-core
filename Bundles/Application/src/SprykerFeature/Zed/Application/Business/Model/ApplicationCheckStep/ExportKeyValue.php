<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep;

use Symfony\Component\Process\Process;

class ExportKeyValue extends AbstractApplicationCheckStep
{

    /**
     */
    public function run()
    {
        $command = 'vendor/bin/console collector:storage:export';

        $this->info('Run ' . $command);

        $process = new Process($command);
        $process->setTimeout(600);

        $process->mustRun(function ($type, $buffer) {
            $this->info($buffer);
        });
    }

}
