<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Pdo;

interface BulkDeleteTouchByIdQueryInterface
{

    /**
     * Adds a delete touch query to the list
     *
     * @param string $tableName
     * @param string $idColumnName
     * @param array  $idsToDelete
     *
     * @return $this
     */
    public function addQuery($tableName, $idColumnName, $idsToDelete);

    /**
     * Generates and returns the bulk SQL-query string for execution
     *
     * @param boolean $cleanup
     *
     * @return string
     */
    public function getRawSqlString($cleanup = true);

}
