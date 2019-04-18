<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Zed\PropelOrm\Business\Generator\Command\SqlInsertCommand;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class InsertSqlConsole extends AbstractPropelOriginCommand
{
    public const COMMAND_NAME = 'propel:sql:insert';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Insert generated SQL into database');

        parent::configure();
    }

    /**
     * @return string
     */
    public function getOriginCommandClassName(): string
    {
        return SqlInsertCommand::class;
    }
}
