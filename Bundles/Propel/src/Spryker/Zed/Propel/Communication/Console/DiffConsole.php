<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Zed\PropelOrm\Business\Generator\Command\MigrationDiffCommand;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class DiffConsole extends AbstractPropelCommandWrapper
{
    public const COMMAND_NAME = 'propel:diff';

    public const PROCESS_TIMEOUT = 300;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Generate diff for Propel2');

        parent::configure();
    }

    /**
     * @return string
     */
    public function getOriginCommandClassName(): string
    {
        return MigrationDiffCommand::class;
    }
}
