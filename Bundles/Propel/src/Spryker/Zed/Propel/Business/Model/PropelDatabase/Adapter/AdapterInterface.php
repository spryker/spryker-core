<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter;

use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\CreateDatabaseInterface;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseInterface;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseTablesInterface;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ExportDatabaseInterface;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ImportDatabaseInterface;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\TableExistenceInterface;

interface AdapterInterface extends CreateDatabaseInterface, DropDatabaseInterface, ExportDatabaseInterface, ImportDatabaseInterface, DropDatabaseTablesInterface, TableExistenceInterface
{
    /**
     * @return string
     */
    public function getEngine();
}
