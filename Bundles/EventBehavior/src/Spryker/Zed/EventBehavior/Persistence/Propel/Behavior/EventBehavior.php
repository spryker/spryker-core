<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventBehavior\Persistence\Propel\Behavior;

use Propel\Generator\Model\Behavior;
use Propel\Generator\Util\PhpParser;
use Propel\Runtime\Exception\PropelException;
use Zend\Filter\Word\UnderscoreToCamelCase;

class EventBehavior extends Behavior
{
    const EVENT_CHANGE_ENTITY_NAME = 'name';
    const EVENT_CHANGE_ENTITY_ID = 'id';
    const EVENT_CHANGE_ENTITY_FOREIGN_KEYS = 'foreignKeys';
    const EVENT_CHANGE_ENTITY_MODIFIED_COLUMNS = 'modifiedColumns';
    const EVENT_CHANGE_NAME = 'event';

    /**
     * @return string
     */
    public function preSave()
    {
        return "
\$this->prepareSaveEventName();
        ";
    }

    /**
     * @return string
     */
    public function postSave()
    {
        return "
\$this->addSaveEventToMemory();
        ";
    }

    /**
     * @return string
     */
    public function postDelete()
    {
        return "
\$this->addDeleteEventToMemory();        
        ";
    }

    /**
     * Adds a single parameter.
     *
     * Expects an associative array looking like
     * [ 'name' => 'foo', 'value' => bar ]
     *
     * @param array $parameter
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function addParameter(array $parameter)
    {
        $parameter = array_change_key_case($parameter, CASE_LOWER);

        $this->parameters[$parameter['name']] = [];

        if (!isset($parameter['column'])) {
            throw new PropelException(sprintf('"column" attribute for %s event behavior is missing', $parameter['name']));
        }

        $this->parameters[$parameter['name']]['column'] = $parameter['column'];

        if (isset($parameter['value'])) {
            $this->parameters[$parameter['name']]['value'] = $parameter['value'];
        }

        if (isset($parameter['operator'])) {
            $this->parameters[$parameter['name']]['operator'] = $parameter['operator'];
        }
    }

    /**
     * @return string
     */
    public function objectAttributes()
    {
        $script = '';
        $script .= $this->addEventAttributes();
        $script .= $this->addForeignKeysAttribute();

        return $script;
    }

    /**
     * @param string $script
     *
     * @return void
     */
    public function objectFilter(&$script)
    {
        $parser = new PhpParser($script, true);
        $eventColumns = $this->getParameters();

        foreach ($eventColumns as $eventColumn) {
            $this->addSetInitialValueStatement($parser, $eventColumn['column']);
        }

        $script = $parser->getCode();
    }

    /**
     * @return string
     */
    public function objectMethods()
    {
        $script = '';
        $script .= $this->addPrepareEventMethod();
        $script .= $this->addToggleEventMethod();
        $script .= $this->addSaveEventMethod();
        $script .= $this->addDeleteEventMethod();
        $script .= $this->addGetForeignKeysMethod();
        $script .= $this->addSaveEventBehaviorEntityChangeMethod();
        $script .= $this->addIsEventColumnsModifiedMethod();
        $script .= $this->addGetPhpType();

        return $script;
    }

    /**
     * @param \Propel\Generator\Util\PhpParser $parser
     * @param string $column
     *
     * @return void
     */
    protected function addSetInitialValueStatement(PhpParser $parser, $column)
    {
        $camelCaseFilter = new UnderscoreToCamelCase();

        $methodName = sprintf('set%s', $camelCaseFilter->filter($column));
        $initialValueField = sprintf("[%sTableMap::COL_%s]", $this->getTable()->getPhpName(), strtoupper($column));

        $methodNamePattern = '(' . $methodName . '\(\$v\)\n[ ]*{)';
        $newMethodCode = preg_replace_callback($methodNamePattern, function ($matches) use ($initialValueField, $column) {
            return $matches[0] . "\n\t\t\$this->_initialValues$initialValueField = \$this->$column;\n";
        }, $parser->findMethod($methodName));

        $parser->replaceMethod($methodName, $newMethodCode);
    }

    /**
     * @return string
     */
    protected function addEventAttributes()
    {
        return "
/**
 * @var string
 */
private \$_eventName;

/**
 * @var bool
 */
private \$_isModified;

/**
 * @var array
 */
private \$_modifiedColumns;

/**
 * @var array
 */
private \$_initialValues;
        
/**
 * @var bool
 */
private \$_isEventDisabled;        
        ";
    }

    /**
     * @return string
     */
    protected function addForeignKeysAttribute()
    {
        $foreignKeys = $this->getTable()->getForeignKeys();
        $tableName = $this->getTable()->getName();
        $implodedForeignKeys = '';

        foreach ($foreignKeys as $foreignKey) {
            $fullColumnName = sprintf("%s.%s", $tableName, $foreignKey->getLocalColumnName());
            $implodedForeignKeys .= sprintf("
    '%s' => '%s',", $fullColumnName, $foreignKey->getLocalColumnName());
        }

        return "
/**
 * @var array
 */
private \$_foreignKeys = [$implodedForeignKeys
];        
        ";
    }

    /**
     * @return string
     */
    protected function addPrepareEventMethod()
    {
        $createEvent = 'Entity.' . $this->getTable()->getName() . '.create';
        $updateEvent = 'Entity.' . $this->getTable()->getName() . '.update';

        return "
/**
 * @return void
 */
protected function prepareSaveEventName()
{
    if (\$this->isNew()) {
        \$this->_eventName = '$createEvent';
    } else {
        \$this->_eventName = '$updateEvent';
    }

    \$this->_modifiedColumns = \$this->getModifiedColumns();
    \$this->_isModified = \$this->isModified();
}
        ";
    }

    /**
     * @return string
     */
    protected function addToggleEventMethod()
    {
        return "
/**
 * @return void
 */
public function disableEvent()
{
    \$this->_isEventDisabled = true;
}

/**
 * @return void
 */
public function enableEvent()
{
    \$this->_isEventDisabled = false;
}        
        ";
    }

    /**
     * @return string
     */
    protected function addSaveEventMethod()
    {
        $tableName = $this->getTable()->getName();
        $dataEventEntityName = static::EVENT_CHANGE_ENTITY_NAME;
        $dataEventEntityId = static::EVENT_CHANGE_ENTITY_ID;
        $dataEventEntityForeignKeys = static::EVENT_CHANGE_ENTITY_FOREIGN_KEYS;
        $dataEventEntityModifiedColumns = static::EVENT_CHANGE_ENTITY_MODIFIED_COLUMNS;
        $dataEventName = static::EVENT_CHANGE_NAME;

        return "
/**
 * @return void
 */
protected function addSaveEventToMemory()
{
    if (\$this->_isEventDisabled) {
        return;
    }
    
    if (\$this->_eventName !== 'Entity.$tableName.create') {       
        if (!\$this->_isModified) {
            return;
        }
        
        if (!\$this->isEventColumnsModified()) {
            return;
        }
    }
    
    \$data = [
        '$dataEventEntityName' => '$tableName',
        '$dataEventEntityId' => \$this->getPrimaryKey(),
        '$dataEventName' => \$this->_eventName,
        '$dataEventEntityForeignKeys' => \$this->getForeignKeys(),
        '$dataEventEntityModifiedColumns' => \$this->_modifiedColumns,
    ];

    \$this->saveEventBehaviorEntityChange(\$data);

    unset(\$this->_eventName);
    unset(\$this->_modifiedColumns);
    unset(\$this->_isModified);
}
        ";
    }

    /**
     * @return string
     */
    protected function addDeleteEventMethod()
    {
        $tableName = $this->getTable()->getName();
        $deleteEvent = 'Entity.' . $tableName . '.delete';
        $dataEventEntityName = static::EVENT_CHANGE_ENTITY_NAME;
        $dataEventEntityId = static::EVENT_CHANGE_ENTITY_ID;
        $dataEventEntityForeignKeys = static::EVENT_CHANGE_ENTITY_FOREIGN_KEYS;
        $dataEventName = static::EVENT_CHANGE_NAME;

        return "
/**
 * @return void
 */
protected function addDeleteEventToMemory()
{
    if (\$this->_isEventDisabled) {
        return;
    }

    \$data = [
        '$dataEventEntityName' => '$tableName',
        '$dataEventEntityId' => \$this->getPrimaryKey(),
        '$dataEventName' => '$deleteEvent',
        '$dataEventEntityForeignKeys' => \$this->getForeignKeys(),
    ];

    \$this->saveEventBehaviorEntityChange(\$data);
}
        ";
    }

    /**
     * @return string
     */
    protected function addGetForeignKeysMethod()
    {
        return "
/**
 * @return array
 */        
protected function getForeignKeys()
{
    \$foreignKeysWithValue = [];
    foreach (\$this->_foreignKeys as \$key => \$value) {
        \$foreignKeysWithValue[\$key] = \$this->getByName(\$value);
    }
    
    return \$foreignKeysWithValue;
}
        ";
    }

    /**
     * @return string
     */
    protected function addSaveEventBehaviorEntityChangeMethod()
    {
        return "
/**
 * @param array \$data
 *
 * @return void
 */
protected function saveEventBehaviorEntityChange(array \$data)
{
    \$spyEventBehaviorEntityChange = new \\Orm\\Zed\\EventBehavior\\Persistence\\SpyEventBehaviorEntityChange();
    \$spyEventBehaviorEntityChange->setData(json_encode(\$data));
    \$spyEventBehaviorEntityChange->setProcessId(\\Spryker\\Zed\\Kernel\\RequestIdentifier::getRequestId());
    \$spyEventBehaviorEntityChange->save();
}        
        ";
    }

    /**
     * @return string
     */
    protected function addIsEventColumnsModifiedMethod()
    {
        $eventParameters = $this->getParameters();
        $tableName = $this->getTable()->getName();
        $implodedModifiedColumns = '';

        foreach ($eventParameters as $eventParameter) {
            if ($eventParameter['column'] === '*') {
                return "
/**
 * @return bool
 */
protected function isEventColumnsModified()
{            
    /* There is a wildcard(*) property for this event */
    return true;
}
            ";
            }
        }

        foreach ($this->getParameters() as $columnAttribute) {
            $implodedAttributes = '';
            foreach ($columnAttribute as $key => $value) {
                $implodedAttributes .= sprintf("
                '$key' => '$value',");
            }

            $implodedModifiedColumns .= sprintf("
            '%s.%s' => [$implodedAttributes
            ],", $tableName, $columnAttribute['column']);
        }

        return "
/**
 * @return bool
 */
protected function isEventColumnsModified()
{
    \$eventColumns = [$implodedModifiedColumns
    ];
    
    foreach (\$this->_modifiedColumns as \$modifiedColumn) {
        if (isset(\$eventColumns[\$modifiedColumn])) {           
            
            if (!isset(\$eventColumns[\$modifiedColumn]['value'])) {
                return true;
            }
            
            \$xmlValue = \$eventColumns[\$modifiedColumn]['value'];
            \$xmlValue = \$this->getPhpType(\$xmlValue, \$modifiedColumn);
            \$xmlOperator = '';
            if (isset(\$eventColumns[\$modifiedColumn]['operator'])) {
                \$xmlOperator = \$eventColumns[\$modifiedColumn]['operator'];
            }
            \$before = \$this->_initialValues[\$modifiedColumn];
            \$field = str_replace('$tableName.', '', \$modifiedColumn);
            \$after = \$this->\$field;
            
            if (\$before === null && \$after !== null) {
                return true;
            }

            if (\$before !== null && \$after === null) {
                return true;
            }

            switch (\$xmlOperator) {
                case '<':
                    \$result = (\$before < \$xmlValue xor \$after < \$xmlValue);
                    break;
                case '>':
                    \$result = (\$before > \$xmlValue xor \$after > \$xmlValue);
                    break;
                case '<=':
                    \$result = (\$before <= \$xmlValue xor \$after <= \$xmlValue);
                    break;
                case '>=':
                    \$result = (\$before >= \$xmlValue xor \$after >= \$xmlValue);
                    break;
                case '<>':
                    \$result = (\$before <> \$xmlValue xor \$after <> \$xmlValue);
                    break;
                case '!=':
                    \$result = (\$before != \$xmlValue xor \$after != \$xmlValue);
                    break;
                case '==':
                    \$result = (\$before == \$xmlValue xor \$after == \$xmlValue);
                    break;
                case '!==':
                    \$result = (\$before !== \$xmlValue xor \$after !== \$xmlValue);
                    break;     
                default:
                    \$result = (\$before === \$xmlValue xor \$after === \$xmlValue);
            }
            
            if (\$result) {
                return true;
            }
        }
    }

    return false;
}        
        ";
    }

    /**
     * @return string
     */
    public function addGetPhpType()
    {
        return "
/**
 * @param string \$xmlValue
 * @param string \$column
 *
 * @return array|bool|\\DateTime|float|int|object
 */
protected function getPhpType(\$xmlValue, \$column)
{
    \$columnType = SpyAvailabilityAbstractTableMap::getTableMap()->getColumn(\$column)->getType();
    if (in_array(strtoupper(\$columnType), ['INTEGER', 'TINYINT', 'SMALLINT'])) {
        \$xmlValue = (int) \$xmlValue;
    } else if (in_array(strtoupper(\$columnType), ['REAL', 'FLOAT', 'DOUBLE', 'BINARY', 'VARBINARY', 'LONGVARBINARY'])) {
        \$xmlValue = (double) \$xmlValue;
    } else if (strtoupper(\$columnType) === 'ARRAY') {
        \$xmlValue = (array) \$xmlValue;
    } else if (strtoupper(\$columnType) === 'BOOLEAN') {
        \$xmlValue = filter_var(\$xmlValue,  FILTER_VALIDATE_BOOLEAN);
    } else if (strtoupper(\$columnType) === 'OBJECT') {
        \$xmlValue = (object) \$xmlValue;
    } else if (in_array(strtoupper(\$columnType), ['DATE', 'TIME', 'TIMESTAMP', 'BU_DATE', 'BU_TIMESTAMP'])) {
        \$xmlValue = \\DateTime::createFromFormat('Y-m-d H:i:s', \$xmlValue);
    }
    
    return \$xmlValue;
}
        ";
    }
}
