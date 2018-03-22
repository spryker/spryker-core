<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Pdo;

interface BulkDeleteTouchByIdQueryInterface
{
    /**
     * @param string $tableName
     * @param string $idColumnName
     * @param array $idsToDelete
     *
     * @return $this
     */
    public function addQuery($tableName, $idColumnName, array $idsToDelete);

    /**
     * @return string
     */
    public function getRawSqlString();

    /**
     * @return void
     */
    public function flushQueries();
}
