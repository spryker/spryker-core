<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Command\Builder;

use Spryker\Zed\Propel\Business\Command\Config\PropelOriginCommandConfigBuilderInterface;
use Spryker\Zed\PropelOrm\Business\Generator\PropelConfigurableInterface;
use Symfony\Component\Console\Command\Command;

class PropelOriginCommandBuilder implements PropelOriginCommandBuilderInterface
{
    /**
     * @var \Spryker\Zed\Propel\Business\Command\Config\PropelOriginCommandConfigBuilderInterface
     */
    protected $commandConfigurator;

    /**
     * @param \Spryker\Zed\Propel\Business\Command\Config\PropelOriginCommandConfigBuilderInterface $commandConfigurator
     */
    public function __construct(PropelOriginCommandConfigBuilderInterface $commandConfigurator)
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
