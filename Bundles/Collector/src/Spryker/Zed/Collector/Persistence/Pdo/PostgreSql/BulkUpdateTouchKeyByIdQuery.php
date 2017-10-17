<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Pdo\PostgreSql;

use Spryker\Zed\Collector\Persistence\Pdo\AbstractBulkTouchQuery;
use Spryker\Zed\Collector\Persistence\Pdo\BulkUpdateTouchKeyByIdQueryInterface;

class BulkUpdateTouchKeyByIdQuery extends AbstractBulkTouchQuery implements BulkUpdateTouchKeyByIdQueryInterface
{
    /**
     * @param string $tableName
     * @param string $keyValue
     * @param string $idColumnName
     * @param int $idValue
     *
     * @return $this
     */
    public function addQuery($tableName, $keyValue, $idColumnName, $idValue)
    {
        $this->queries[] = sprintf(
            $this->getQueryTemplate(),
            $tableName,
            $keyValue,
            $idColumnName,
            $idValue
        );

        return $this;
    }

    /**
     * @return string
     */
    protected function getQueryTemplate()
    {
        return "UPDATE %s SET key = '%s' WHERE %s = '%s'";
    }
}
