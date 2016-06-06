<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Pdo;

interface BulkUpdateTouchKeyByIdQueryInterface
{

    /**
     * Adds an update touch query to the list of queries
     *
     * @param string $tableName
     * @param string $keyValue
     * @param string $idColumnName
     * @param int    $idValue
     *
     * @return $this
     */
    public function addQuery($tableName, $keyValue, $idColumnName, $idValue);

    /**
     * Generates and returns the bulk SQL-query string for execution
     *
     * @param boolean $cleanup
     *
     * @return string
     */
    public function getRawSqlString($cleanup = true);

}
