<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Command\Runner;

use Spryker\Zed\Propel\Business\Command\Builder\PropelOriginCommandBuilderInterface;
use Spryker\Zed\Propel\Business\Command\Input\PropelCommandInputBuilderInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

class PropelOriginCommandRunner implements PropelOriginCommandRunnerInterface
{
    /**
     * @var \Spryker\Zed\Propel\Business\Command\Input\PropelCommandInputBuilderInterface
     */
    protected $inputBuilder;

    /**
     * @var \Spryker\Zed\Propel\Business\Command\Builder\PropelOriginCommandBuilderInterface
     */
    protected $originCommandBuilder;

    /**
     * @param \Spryker\Zed\Propel\Business\Command\Builder\PropelOriginCommandBuilderInterface $originCommandBuilder
     * @param \Spryker\Zed\Propel\Business\Command\Input\PropelCommandInputBuilderInterface $inputBuilder
     */
    public function __construct(
        PropelOriginCommandBuilderInterface $originCommandBuilder,
        PropelCommandInputBuilderInterface $inputBuilder
    ) {
        $this->inputBuilder = $inputBuilder;
        $this->originCommandBuilder = $originCommandBuilder;
    }

    /**
     * @param string $propelOriginalCommandClassName
     * @param \Symfony\Component\Console\Input\InputDefinition $propelCommandDefinition
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function runOriginCommand(
        string $propelOriginalCommandClassName,
        InputDefinition $propelCommandDefinition,
        OutputInterface $output
    ): int {
        $propelOriginalCommand = $this->originCommandBuilder->createOriginCommand($propelOriginalCommandClassName);
        $input = $this->inputBuilder
            ->buildInput(
                $propelCommandDefinition,
                $propelOriginalCommand->getDefinition()
            );

        return $propelOriginalCommand->run($input, $output);
    }
}
