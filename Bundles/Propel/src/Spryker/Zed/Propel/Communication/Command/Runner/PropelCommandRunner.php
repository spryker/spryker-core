<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Runner;

use Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface;
use Spryker\Zed\Propel\Communication\Command\Input\PropelCommandInputBuilderInterface;
use Spryker\Zed\PropelOrm\Communication\Generator\ConfigurablePropelCommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class PropelCommandRunner implements PropelCommandRunnerInterface
{
    /**
     * @var \Spryker\Zed\Propel\Communication\Command\Input\PropelCommandInputBuilderInterface
     */
    protected $inputBuilder;

    /**
     * @var \Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface
     */
    protected $propelCommandConfigurator;

    /**
     * @param \Spryker\Zed\Propel\Communication\Command\Input\PropelCommandInputBuilderInterface $inputBuilder
     * @param \Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface $propelCommandConfigurator
     */
    public function __construct(
        PropelCommandInputBuilderInterface $inputBuilder,
        PropelCommandConfiguratorInterface $propelCommandConfigurator
    ) {
        $this->inputBuilder = $inputBuilder;
        $this->propelCommandConfigurator = $propelCommandConfigurator;
    }

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
    ): int {
        if ($command instanceof ConfigurablePropelCommandInterface) {
            $this->propelCommandConfigurator->configurePropelCommand($command);
        }

        $input = $this->inputBuilder
            ->buildInput(
                $inputDefinition,
                $command
            );

        return $command->run($input, $output);
    }
}
