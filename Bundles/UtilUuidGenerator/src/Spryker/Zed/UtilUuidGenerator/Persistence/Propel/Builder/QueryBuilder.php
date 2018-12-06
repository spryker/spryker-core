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
    protected const TABLE_PREFIX = 'Spy';
    protected const QUERY_NAMESPACE = 'Orm\Zed\%s\Persistence\%sQuery';
    protected const CAMEL_CASE_REGEXP = '/(^[^A-Z]+|[A-Z][^A-Z]+)/';

    /**
     * @param string $tableName
     *
     * @throws \Exception
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQuery(string $tableName): ModelCriteria
    {
        $queryClassNames = $this->getAvailableClassNames($tableName);

        foreach ($queryClassNames as $queryClassName) {
            if (class_exists($queryClassName)) {
                return new $queryClassName();
            }
        }

        throw new Exception("Query for table '{$tableName}' not found.");
    }

    /**
     * @param string $tableName
     *
     * @return string[]
     */
    protected function getAvailableClassNames(string $tableName): array
    {
        $className = $this->camelizeTableName($tableName);
        $classWithoutPrefix = str_replace(static::TABLE_PREFIX, '', $className);

        $availableClassNames = [];
        $classFragments = $this->splitCamelCase($classWithoutPrefix);
        $fragment = '';

        foreach ($classFragments as $classFragment) {
            $fragment .= $classFragment;
            $availableClassNames[] = sprintf(static::QUERY_NAMESPACE, $fragment, $className);
        }

        return $availableClassNames;
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

    /**
     * @param string $className
     *
     * @return array
     */
    protected function splitCamelCase(string $className): array
    {
        return preg_split(
            static::CAMEL_CASE_REGEXP,
            $className,
            -1,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );
    }
}
