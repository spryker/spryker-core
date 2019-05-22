<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Builder;

use Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface;
use Spryker\Zed\PropelOrm\Business\Generator\ConfigurablePropelCommandInterface;
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
     * @param string $originalPropelCommandClassName
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createCommand(string $originalPropelCommandClassName): Command
    {
        $originalCommand = $this->instantiateCommand($originalPropelCommandClassName);

        if ($originalCommand instanceof ConfigurablePropelCommandInterface) {
            $this->propelCommandConfigurator->configurePropelCommand($originalCommand);
        }

        return $originalCommand;
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
