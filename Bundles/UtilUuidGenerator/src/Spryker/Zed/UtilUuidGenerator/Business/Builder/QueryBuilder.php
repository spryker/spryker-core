<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Business\Builder;

use Exception;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class QueryBuilder implements QueryBuilderInterface
{
    protected const TABLE_SEPARATOR = '_';
    protected const TABLE_PREFIX = 'Spy';
    protected const QUERY_NAMESPACE = 'Orm\Zed\%s\Persistence\%sQuery';

    /**
     * @param string $tableName
     *
     * @throws \Exception
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQuery(string $tableName): ModelCriteria
    {
        $className = $this->getClassNameWithNamespace($tableName);

        if (!class_exists($className)) {
            throw new Exception("Query '{$className}' not found.");
        }

        return new $className();
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    protected function getClassNameWithNamespace(string $tableName): string
    {
        $className = $this->camelizeTableName($tableName);
        $classWithoutPrefix = str_replace(static::TABLE_PREFIX, '', $className);

        return sprintf(static::QUERY_NAMESPACE, $classWithoutPrefix, $className);
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    protected function camelizeTableName(string $tableName): string
    {
        return str_replace(static::TABLE_SEPARATOR, '', ucwords($tableName, static::TABLE_SEPARATOR));
    }
}
