<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\MySql;

use Propel\Runtime\Propel;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseTablesInterface;

class DropMySqlDatabaseTables implements DropDatabaseTablesInterface
{
    /**
     * @return void
     */
    public function dropTables(): void
    {
        $conn = Propel::getConnection();
        $conn->exec($this->getDropQuery());
    }

    /**
     * @return string
     */
    protected function getDropQuery(): string
    {
        return "
            SELECT concat('KILL ',id,';') from information_schema.processlist where db = (SELECT DATABASE()); 
            
            SET FOREIGN_KEY_CHECKS = 0;
            SELECT CONCAT('DROP TABLE IF EXISTS `', GROUP_CONCAT(table_name SEPARATOR '`, `'), '`;')
                FROM information_schema.tables
                WHERE table_schema = (SELECT DATABASE()) INTO @dropTableQuery;
            
            PREPARE stmt FROM @dropTableQuery;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
            SET FOREIGN_KEY_CHECKS = 1;
        ";
    }
}
