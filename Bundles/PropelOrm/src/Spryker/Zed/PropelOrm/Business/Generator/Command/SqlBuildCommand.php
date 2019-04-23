<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm\Business\Generator\Command;

use Propel\Generator\Command\SqlBuildCommand as OriginalPropelSqlBuildCommand;
use Spryker\Zed\PropelOrm\Business\Generator\PropelConfigurableInterface;
use Spryker\Zed\PropelOrm\Business\Generator\PropelConfiguratorTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SqlBuildCommand extends OriginalPropelSqlBuildCommand implements PropelConfigurableInterface
{
    use PropelConfiguratorTrait;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        return (int)$this->execute($input, $output);
    }
}
