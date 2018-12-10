<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Persistence\Propel\Builder;

use Exception;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class QueryBuilder implements QueryBuilderInterface
{
    protected const TABLE_SEPARATOR = '_';
    protected const QUERY_NAMESPACE = 'Orm\Zed\%s\Persistence\%sQuery';

    /**
     * @param string $tableAlias
     *
     * @throws \Exception
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQuery(string $tableAlias): ModelCriteria
    {
        $moduleName = $this->getModuleName($tableAlias);
        $tableName = $this->getTableName($tableAlias);

        $className = $this->getFullyQualifiedClassName($moduleName, $tableName);

        if (!class_exists($className)) {
            throw new Exception("Query '{$className}' not found.");
        }

        return new $className();
    }

    /**
     * @param string $tableAlias
     *
     * @return string
     */
    protected function getModuleName(string $tableAlias): string
    {
        $components = explode('.', $tableAlias);

        return reset($components);
    }

    /**
     * @param string $tableAlias
     *
     * @return string
     */
    protected function getTableName(string $tableAlias): string
    {
        $components = explode('.', $tableAlias);

        return end($components);
    }

    /**
     * @param string $moduleName
     * @param string $tableName
     *
     * @return string
     */
    protected function getFullyQualifiedClassName(string $moduleName, string $tableName): string
    {
        return sprintf(static::QUERY_NAMESPACE, $moduleName, $this->camelizeTableName($tableName));
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
