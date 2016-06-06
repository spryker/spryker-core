<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Pdo\PostgreSql;

use Spryker\Zed\Collector\Persistence\Pdo\BulkUpdateTouchKeyByIdQueryInterface;

class BulkUpdateTouchKeyByIdQuery implements BulkUpdateTouchKeyByIdQueryInterface
{

    const QUERY_TEMPLATE = "UPDATE %s SET key = '%s' WHERE %s = '%s'";
    const QUERY_GLUE = "; \n";

    /**
     * Name of the table to bulk update touch information for
     *
     * @var string
     */
    protected $tableName;

    /**
     * Name of the "ID" column to bulk update touch information for
     *
     * @var string
     */
    protected $idColumnName;

    /**
     * List of queries for bulk-update operation
     *
     * @var array
     */
    protected $queries = [];

    /**
     * @param string $tableName
     * @param string $keyValue
     * @param string $idColumnName
     * @param int    $idValue
     *
     * @return $this
     */
    public function addQuery($tableName, $keyValue, $idColumnName, $idValue)
    {
        $this->queries[] = sprintf(
            static::QUERY_TEMPLATE,
            $tableName,
            $keyValue,
            $idColumnName,
            $idValue
        );

        return $this;
    }

    /**
     * @param boolean $cleanup
     *
     * @return string
     */
    public function getRawSqlString($cleanup = true)
    {
        $queryString = implode(static::QUERY_GLUE, $this->queries);

        if ($cleanup) {
            $this->queries = [];
        }

        return $queryString;
    }

}
