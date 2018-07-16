<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KeyGenerationBehavior\Persistence\Propel\Behavior;

use Propel\Generator\Model\Behavior;
use Propel\Generator\Model\PropelTypes;
use Propel\Generator\Model\Unique;
use Spryker\Zed\KeyGenerationBehavior\Persistence\Propel\Behavior\Exception\MissingAttributeException;
use Zend\Filter\Word\UnderscoreToCamelCase;

class KeyGenerationBehavior extends Behavior
{
    protected const KEY_COLUMN_NAME = 'api_key';
    protected const KEY_COLUMN_UNIQUE_INDEX_POSTFIX = '-unique-api-key';

    protected const ERROR_MISSING_KEY_PREFIX_PARAMETER = '%s misses "key_prefix" parameter';
    protected const ERROR_INVALID_COLUMNS_TO_KEY_NAME_PARAMETER = 'Invalid JSON passed to %s as "columns_to_key_name" parameter';

    /**
     * @var array
     */
    protected $parameters = [
        'key_prefix' => null,
        'columns_to_key_name' => [],
    ];

    /**
     * @return string
     */
    public function objectAttributes(): string
    {
        $script = '';
        $script .= $this->addBaseAttribute();

        return $script;
    }

    /**
     * @return string
     */
    public function preSave(): string
    {
        return '$this->setGeneratedKey();';
    }

    /**
     * @return string
     */
    public function objectMethods(): string
    {
        $script = '';
        $script .= $this->addGetUuidGeneratorServiceMethod();
        $script .= $this->addSetGeneratedKeyMethod();

        return $script;
    }

    /**
     * @return void
     */
    public function modifyTable(): void
    {
        $table = $this->getTable();

        if (!$table->hasColumn(static::KEY_COLUMN_NAME)) {
            $column = $table->addColumn([
                'name' => static::KEY_COLUMN_NAME,
                'type' => PropelTypes::VARCHAR,
            ]);

            $uniqueIndex = new Unique();
            $uniqueIndex->setName($table->getName() . '-unique-api-key');
            $uniqueIndex->addColumn($column);
            $table->addUnique($uniqueIndex);
        }
    }

    /**
     * @return string
     */
    public function addBaseAttribute(): string
    {
        return $this->renderTemplate('objectBaseAttribute');
    }

    /**
     * @return string
     */
    protected function addGetUuidGeneratorServiceMethod(): string
    {
        return $this->renderTemplate('objectGetUuidGeneratorService');
    }

    /**
     * @throws \Spryker\Zed\KeyGenerationBehavior\Persistence\Propel\Behavior\Exception\MissingAttributeException
     *
     * @return string
     */
    protected function addSetGeneratedKeyMethod(): string
    {
        $parameters = $this->getParameters();
        $keyValues = null;

        if (!isset($parameters['key_prefix'])) {
            throw new MissingAttributeException(
                sprintf(static::ERROR_MISSING_KEY_PREFIX_PARAMETER, $this->getTable()->getPhpName())
            );
        }

        $name = '\'' . $parameters['key_prefix'] . '\'';
        if ($columns = $this->getColumnsToKeyNameParameter()) {
            $columns = explode('.', $columns);
            if (!is_array($columns)) {
                throw new MissingAttributeException(
                    sprintf(static::ERROR_INVALID_COLUMNS_TO_KEY_NAME_PARAMETER, $this->getTable()->getPhpName())
                );
            }

            $filter = new UnderscoreToCamelCase();
            foreach ($columns as $column) {
                $keyValues[] = sprintf('get%s()', $filter->filter($column));
            }
        }

        if ($keyValues !== null) {
            foreach ($keyValues as $keyValue) {
                $name .= " . '.' . \$this->{$keyValue}";
            }
        }

        return $this->renderTemplate('objectSetGeneratedKey', [
            'name' => $name,
        ]);
    }

    /**
     * @return string
     */
    protected function getColumnsToKeyNameParameter(): string
    {
        $columns = '';
        if (isset($this->getParameters()['columns_to_key_name'])) {
            $columns = $this->getParameters()['columns_to_key_name'];
        }

        return $columns;
    }
}
