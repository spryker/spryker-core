<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Persistence\FieldMapper;

use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Propel\Runtime\Map\TableMap;

class FieldMapper implements FieldMapperInterface
{

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomerQuery $query
     * @param array $allowedFields
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function mapFields(SpyCustomerQuery $query, array $allowedFields)
    {
        $query->clearSelectColumns();

        if (empty($allowedFields)) {
            return $query;
        }

        $columns = SpyCustomerTableMap::getFieldNames(TableMap::TYPE_FIELDNAME);

        $allowedColumns = array_intersect_key(
            array_flip($columns),
            array_flip($allowedFields)
        );

        $selectedColumns = [];
        foreach ($allowedColumns as $columnAlias => $index) {
            $columnName = SpyCustomerTableMap::TABLE_NAME . '.' . $columnAlias;
            $selectedColumns[] = $columnName;

            $query->withColumn($columnName, $columnAlias);
        }

        $query->select($selectedColumns);

        return $query;
    }

}
