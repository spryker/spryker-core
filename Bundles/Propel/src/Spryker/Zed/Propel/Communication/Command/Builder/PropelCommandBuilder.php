<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Builder;

use Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface;
use Spryker\Zed\PropelOrm\Business\Generator\PropelConfigurableInterface;
use Symfony\Component\Console\Command\Command;

class PropelCommandBuilder implements PropelCommandBuilderInterface
{
    /**
     * @var \Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface
     */
    protected $propelCommandConfigurator;

    /**
     * @param \Spryker\Zed\Propel\Communication\Command\Config\PropelCommandConfiguratorInterface $propelCommandConfigurationBuilder
     */
    public function __construct(PropelCommandConfiguratorInterface $propelCommandConfigurationBuilder)
    {
        $this->propelCommandConfigurator = $propelCommandConfigurationBuilder;
    }

    /**
     * @param string $originalPropelCommandClassName
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function createOriginCommand(string $originalPropelCommandClassName): Command
    {
        $originCommand = $this->createCommand($originalPropelCommandClassName);

        if ($originCommand instanceof PropelConfigurableInterface) {
            $this->propelCommandConfigurator->propelConfigurable($originCommand);
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
