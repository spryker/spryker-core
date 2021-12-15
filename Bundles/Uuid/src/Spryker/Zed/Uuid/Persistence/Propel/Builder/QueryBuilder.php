<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Persistence\Propel\Builder;

use Exception;
use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class QueryBuilder implements QueryBuilderInterface
{
    /**
     * @var string
     */
    protected const TABLE_SEPARATOR = '_';

    /**
     * @var string
     */
    protected const QUERY_NAMESPACE = 'Orm\Zed\%s\Persistence\%sQuery';

    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @throws \Exception
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQuery(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): ModelCriteria
    {
        $className = $this->getFullyQualifiedClassName(
            $uuidGeneratorConfigurationTransfer->getModule(),
            $uuidGeneratorConfigurationTransfer->getTable(),
        );

        if (!class_exists($className)) {
            throw new Exception("Query '{$className}' not found.");
        }
        /** @var \Propel\Runtime\ActiveQuery\ModelCriteria $class */
        $class = new $className();

        return $class;
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
