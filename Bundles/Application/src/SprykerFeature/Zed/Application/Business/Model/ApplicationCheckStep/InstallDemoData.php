<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use Symfony\Component\Process\Process;

class InstallDemoData extends AbstractApplicationCheckStep
{

    /**
     * @return bool
     */
    public function run()
    {
        $command = 'vendor/bin/console setup:install-demo-data';

        $this->info('Run ' . $command);

        $process = new Process($command);
        $process->setTimeout(600);

        $process->mustRun(function ($type, $buffer) {
            $this->info($buffer);
        });
    }

}
