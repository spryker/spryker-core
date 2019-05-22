<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql;

use Propel\Runtime\Propel;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseTablesInterface;

class DropPostgreSqlDatabaseTables implements DropDatabaseTablesInterface
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
            DO $$
                DECLARE
                    r RECORD;
                
                BEGIN
                    PERFORM pg_terminate_backend(pid)
                        FROM pg_stat_activity
                        WHERE pg_stat_activity.datname = current_database() AND pid <> pg_backend_pid();
                
                FOR r IN (SELECT tablename FROM pg_tables WHERE schemaname = current_schema())
                LOOP
                    EXECUTE 'DROP TABLE IF EXISTS ' || quote_ident(r.tablename) || ' CASCADE';
                END LOOP;
                
                FOR r IN (SELECT sequence_name FROM information_schema.sequences where sequence_schema = current_schema())
                LOOP
                    EXECUTE 'DROP SEQUENCE IF EXISTS ' || quote_ident(r.sequence_name) || ' CASCADE';
                END LOOP;
            END $$; 
        ";
    }
}
