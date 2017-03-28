<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Persistence\Mapper;

use Propel\Runtime\ActiveQuery\ModelCriteria;

class FieldMapper implements FieldMapperInterface
{

    /**
     * @param string $tableName
     * @param array $tableFields
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param array $allowedFields
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapFields($tableName, array $tableFields, ModelCriteria $query, array $allowedFields)
    {
        if (empty($allowedFields)) {
            $allowedFields = $tableFields;
        }

        $allowedColumns = array_intersect_key(
            array_flip($tableFields),
            array_flip($allowedFields)
        );

        $query->clearSelectColumns();

        $selectedColumns = [];
        foreach ($allowedColumns as $columnAlias => $index) {
            $columnName = $tableName . '.' . $columnAlias;
            $selectedColumns[] = $columnName;

            $query->withColumn($columnName, $columnAlias);
        }

        $query->select($selectedColumns);

        return $query;
    }

}
