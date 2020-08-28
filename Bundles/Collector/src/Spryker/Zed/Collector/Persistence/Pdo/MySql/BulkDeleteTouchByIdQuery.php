<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Pdo\MySql;

use Spryker\Zed\Collector\Persistence\Pdo\AbstractBulkTouchQuery;
use Spryker\Zed\Collector\Persistence\Pdo\BulkDeleteTouchByIdQueryInterface;

class BulkDeleteTouchByIdQuery extends AbstractBulkTouchQuery implements BulkDeleteTouchByIdQueryInterface
{
    /**
     * @param string $tableName
     * @param string $idColumnName
     * @param array $idsToDelete
     *
     * @return $this
     */
    public function addQuery($tableName, $idColumnName, array $idsToDelete)
    {
        $this->queries[] = sprintf(
            $this->getQueryTemplate(),
            $tableName,
            $idColumnName,
            $this->arrayToSqlValueString($idsToDelete)
        );

        return $this;
    }

    /**
     * @return string
     */
    protected function getQueryTemplate()
    {
        return 'DELETE FROM `%s` WHERE `%s` IN (%s)';
    }
}
