<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Runner;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

interface PropelCommandRunnerInterface
{
    /**
     * @param \Symfony\Component\Console\Command\Command $command
     * @param \Symfony\Component\Console\Input\InputDefinition $inputDefinition
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function runCommand(
        Command $command,
        InputDefinition $inputDefinition,
        OutputInterface $output
    ): int;
}
