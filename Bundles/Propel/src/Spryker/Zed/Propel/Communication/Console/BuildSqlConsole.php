<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Zed\PropelOrm\Business\Generator\Command\SqlBuildCommand;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class BuildSqlConsole extends AbstractPropelCommandWrapper
{
    public const COMMAND_NAME = 'propel:sql:build';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Build SQL with Propel2');

        parent::configure();
    }

    /**
     * @return string
     */
    public function getOriginCommandClassName(): string
    {
        return SqlBuildCommand::class;
    }
}
