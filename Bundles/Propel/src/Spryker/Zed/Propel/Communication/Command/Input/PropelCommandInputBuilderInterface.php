<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Input;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

interface PropelCommandInputBuilderInterface
{
    /**
     * @param \Symfony\Component\Console\Input\InputDefinition $inputDefinition
     * @param \Symfony\Component\Console\Command\Command $command
     *
     * @return \Symfony\Component\Console\Input\InputInterface
     */
    public function buildInput(InputDefinition $inputDefinition, Command $command): InputInterface;
}
