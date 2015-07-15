<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\DbDump;

use SprykerFeature\Shared\Library\ConfigInterface;

interface DbDumpConfig extends ConfigInterface
{

    const DB_DUMP_USERNAME = 'DB_DUMP_USERNAME';
    const DB_DUMP_PASSWORD = 'DB_DUMP_PASSWORD';
    const DB_DUMP_DATABASE = 'DB_DUMP_DATABASE';
    const DB_DUMP_HOST = 'DB_DUMP_HOST';
    const DB_DUMP_MYSQLDUMP_BIN = 'DB_DUMP_MYSQLDUMP_BIN';
    const DB_DUMP_MYSQL_BIN = 'DB_DUMP_MYSQL_BIN';

}
