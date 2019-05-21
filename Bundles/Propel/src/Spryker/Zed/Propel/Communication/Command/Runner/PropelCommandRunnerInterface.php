<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Runner;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */
interface PropelCommandRunnerInterface
{
    /**
     * @param \Symfony\Component\Console\Command\Command $propelOriginalCommand
     * @param \Symfony\Component\Console\Input\InputDefinition $propelCommandDefinition
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function runOriginalCommand(
        Command $propelOriginalCommand,
        InputDefinition $propelCommandDefinition,
        OutputInterface $output
    ): int;
}
