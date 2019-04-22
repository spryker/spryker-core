<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Builder;

use Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfigBuilderInterface;
use Spryker\Zed\PropelOrm\Business\Generator\PropelConfigurableInterface;
use Symfony\Component\Console\Command\Command;

class PropelCommandBuilder implements PropelCommandBuilderInterface
{
    /**
     * @var \Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfigBuilderInterface
     */
    protected $commandConfigurator;

    /**
     * @param \Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfigBuilderInterface $commandConfigurator
     */
    public function __construct(PropelCommandConfigBuilderInterface $commandConfigurator)
    {
        $this->commandConfigurator = $commandConfigurator;
    }

    /**
     * @param string $originPropelCommandClassName
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createOriginCommand(string $originPropelCommandClassName): Command
    {
        $originCommand = $this->createCommand($originPropelCommandClassName);

        if ($originCommand instanceof PropelConfigurableInterface) {
            $this->commandConfigurator->configureCommand($originCommand);
        }

        return $originCommand;
    }

    /**
     * @param string $commandClassName
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    protected function createCommand(string $commandClassName): Command
    {
        return new $commandClassName();
    }
}
