<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Input;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class PropelCommandInputBuilder implements PropelCommandInputBuilderInterface
{
    /**
     * @var string
     */
    protected const PARAM_COMMAND = 'command';

    /**
     * @param \Symfony\Component\Console\Input\InputDefinition $inputDefinition
     * @param \Symfony\Component\Console\Input\InputDefinition $wrappedInputDefinition
     * @param string $commandName
     * @param array<string, mixed> $commandLineArguments
     *
     * @return \Symfony\Component\Console\Input\InputInterface
     */
    public function buildInput(
        InputDefinition $inputDefinition,
        InputDefinition $wrappedInputDefinition,
        string $commandName,
        array $commandLineArguments = []
    ): InputInterface {
        $wrappedInputDefinition->addArguments(
            $inputDefinition->getArguments(),
        );

        $wrappedInputDefinition->addOptions(
            $inputDefinition->getOptions(),
        );

        $parameters = [static::PARAM_COMMAND => $commandName];
        $parameters += $commandLineArguments;

        return new ArrayInput($parameters, $wrappedInputDefinition);
    }
}
