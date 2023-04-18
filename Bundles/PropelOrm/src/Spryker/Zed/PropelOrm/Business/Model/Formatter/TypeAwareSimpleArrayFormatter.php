<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm\Business\Model\Formatter;

use Propel\Generator\Model\PropelTypes;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Propel\Runtime\Map\DatabaseMap;
use Propel\Runtime\Propel;

/**
 * Array formatter for Propel select query
 * format() returns a ArrayCollection of associative arrays, a string,
 * or an array and ensures that boolean type column values are returned as booleans.
 */
class TypeAwareSimpleArrayFormatter extends SimpleArrayFormatter
{
    /**
     * @param array $row
     *
     * @return array|string|false
     */
    public function getStructuredArrayFromRow(array $row)
    {
        $rowFromParent = parent::getStructuredArrayFromRow($row);

        if (!$rowFromParent || !is_array($rowFromParent)) {
            return $rowFromParent;
        }

        $normalizedAsColumnsArray = $this->getNormalizedAsColumnsArray();

        foreach ($rowFromParent as $key => $value) {
            $tableColumnArray = $this->getTableColumnArray($normalizedAsColumnsArray[$key]);

            if (!$this->canFindColumnInTableMap($tableColumnArray)) {
                continue;
            }

            if ($this->isBooleanColumnType($tableColumnArray)) {
                $rowFromParent[$key] = (bool)$value;
            }
        }

        return $rowFromParent;
    }

    /**
     * @return array
     */
    protected function getNormalizedAsColumnsArray(): array
    {
        $fullySpecifiedQueryColumnNames = $this->getAsColumns();

        $normalizedValues = $this->normalizeArray($fullySpecifiedQueryColumnNames);
        $normalizedKeys = $this->normalizeArray(array_keys($fullySpecifiedQueryColumnNames));

        return array_combine($normalizedKeys, $normalizedValues);
    }

    /**
     * @param array $tableColumnArray
     *
     * @return bool
     */
    protected function canFindColumnInTableMap(array $tableColumnArray): bool
    {
        if (!$this->isArrayOfTwoItems($tableColumnArray)) {
            return false;
        }

        $tableExists = $this->getDatabaseMap()->hasTable($this->getTableName($tableColumnArray));

        if (!$tableExists) {
            return false;
        }

        return $this->getDatabaseMap()
            ->getTable($this->getTableName($tableColumnArray))
            ->hasColumn($this->getColumnName($tableColumnArray));
    }

    /**
     * @param array $tableColumnArray
     *
     * @return bool
     */
    protected function isArrayOfTwoItems(array $tableColumnArray): bool
    {
        return count($tableColumnArray) === 2 && is_string($tableColumnArray[0]) && is_string($tableColumnArray[1]);
    }

    /**
     * @return \Propel\Runtime\Map\DatabaseMap
     */
    protected function getDatabaseMap(): DatabaseMap
    {
        return Propel::getServiceContainer()->getDatabaseMap($this->dbName);
    }

    /**
     * @param string $fullyQualifiedColumnName
     *
     * @return array<string>
     */
    protected function getTableColumnArray(string $fullyQualifiedColumnName): array
    {
        return explode('.', $fullyQualifiedColumnName);
    }

    /**
     * @param array $tableColumnArray
     *
     * @return bool
     */
    protected function isBooleanColumnType(array $tableColumnArray): bool
    {
        $columnType = $this->getDatabaseMap()
            ->getTable($this->getTableName($tableColumnArray))
            ->getColumn($this->getColumnName($tableColumnArray))
            ->getType();

        return $columnType === PropelTypes::BOOLEAN;
    }

    /**
     * @param array $tableColumnArray
     *
     * @return string
     */
    protected function getTableName(array $tableColumnArray): string
    {
        return $tableColumnArray[0] ?? '';
    }

    /**
     * @param array $tableColumnArray
     *
     * @return string
     */
    protected function getColumnName(array $tableColumnArray): string
    {
        return $tableColumnArray[1] ?? '';
    }

    /**
     * @param array $array
     *
     * @return array
     */
    protected function normalizeArray(array $array): array
    {
        return array_map(function ($arrayElement) {
            return str_replace(['`', '"'], '', $arrayElement);
        }, $array);
    }
}
