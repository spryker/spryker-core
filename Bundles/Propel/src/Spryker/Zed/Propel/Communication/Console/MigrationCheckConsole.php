<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Zed\PropelOrm\Business\Generator\Command\MigrationStatusCommand;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class MigrationCheckConsole extends AbstractPropelCommandWrapper
{
    public const COMMAND_NAME = 'propel:migration:check';
    public const CODE_CHANGES = 3;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Checks if migration needs to be executed. Scripts can use return code ' . static::CODE_SUCCESS . ' (all good) vs ' . static::CODE_CHANGES . ' (migration needed).');

        parent::configure();
    }

    /**
     * @return string
     */
    public function getOriginCommandClassName(): string
    {
        return MigrationStatusCommand::class;
    }
}
