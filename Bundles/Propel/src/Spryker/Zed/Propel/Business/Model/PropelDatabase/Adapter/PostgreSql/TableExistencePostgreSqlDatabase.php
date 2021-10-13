<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql;

use Propel\Runtime\Propel;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\TableExistenceInterface;

class TableExistencePostgreSqlDatabase implements TableExistenceInterface
{
    /**
     * @param string $tableName
     *
     * @return bool
     */
    public function tableExists(string $tableName): bool
    {
        $connection = Propel::getConnection();
        $query = 'SELECT 1 FROM information_schema.tables WHERE table_name = ?;';

        /** @var \PDOStatement $statement */
        $statement = $connection->prepare($query);
        $statement->execute([$tableName]);
        $result = $statement->fetch();

        return (bool)$result;
    }
}
