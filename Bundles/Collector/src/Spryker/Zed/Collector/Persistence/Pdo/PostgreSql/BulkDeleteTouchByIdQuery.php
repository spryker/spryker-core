<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Pdo\PostgreSql;

use Spryker\Zed\Collector\Persistence\Pdo\BulkDeleteTouchByIdQueryInterface;

class BulkDeleteTouchByIdQuery implements BulkDeleteTouchByIdQueryInterface
{

    const QUERY_TEMPLATE = "DELETE FROM %s WHERE %s IN (%s)";
    const QUERY_GLUE = "; \n";

    /**
     * Name of the table to bulk delete touch information from
     *
     * @var string
     */
    protected $tableName;

    /**
     * Name of the "ID" column to bulk delete touch information by
     *
     * @var string
     */
    protected $idColumnName;

    /**
     * List of queries for bulk-delete operation
     *
     * @var array
     */
    protected $queries = [];

    /**
     * @param string $tableName
     * @param string $idColumnName
     * @param array  $idsToDelete
     *
     * @return $this
     */
    public function addQuery($tableName, $idColumnName, $idsToDelete)
    {
        $this->queries[] = sprintf(
            static::QUERY_TEMPLATE,
            $tableName,
            $idColumnName,
            implode(',', $idsToDelete)
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
