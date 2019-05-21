<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Zed\PropelOrm\Business\Generator\Command\MigrationMigrateCommand;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class MigrateConsole extends AbstractPropelCommandWrapper
{
    public const COMMAND_NAME = 'propel:migrate';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Migrate database');

        parent::configure();
    }

    /**
     * @return string
     */
    public function getOriginalCommandClassName(): string
    {
        return MigrationMigrateCommand::class;
    }
}
