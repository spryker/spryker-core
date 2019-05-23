<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Builder;

use Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface;
use Spryker\Zed\PropelOrm\Communication\Generator\ConfigurablePropelCommandInterface;
use Symfony\Component\Console\Command\Command;

class PropelCommandBuilder implements PropelCommandBuilderInterface
{
    /**
     * @var \Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface
     */
    protected $propelCommandConfigurator;

    /**
     * @param \Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface $propelCommandConfigurator
     */
    public function __construct(PropelCommandConfiguratorInterface $propelCommandConfigurator)
    {
        $this->propelCommandConfigurator = $propelCommandConfigurator;
    }

    /**
     * @param string $propelCommandClassName
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createCommand(string $propelCommandClassName): Command
    {
        $command = $this->instantiateCommand($propelCommandClassName);

        if ($command instanceof ConfigurablePropelCommandInterface) {
            $this->propelCommandConfigurator->configurePropelCommand($command);
        }

        return $command;
    }

    /**
     * @param string $commandClassName
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    protected function instantiateCommand(string $commandClassName): Command
    {
        return new $commandClassName();
    }
}
