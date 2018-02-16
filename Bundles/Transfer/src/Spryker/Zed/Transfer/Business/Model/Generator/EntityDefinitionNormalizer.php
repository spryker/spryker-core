<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Spryker\Zed\Transfer\Business\Model\Generator\Helper\PluralizerInterface;
use Zend\Filter\Word\UnderscoreToCamelCase;

class EntityDefinitionNormalizer extends DefinitionNormalizer
{
    const KEY_TYPE = 'type';
    const KEY_COLUMN = 'column';
    const KEY_FOREIGN_KEY = 'foreign-key';
    const KEY_ENTITY = 'Entity';
    const FOREIGN_TABLE = 'foreignTable';
    const KEY_PHP_NAME = 'phpName';
    const ENTITY_NAMESPACE = 'entity-namespace';

    /**
     * @var \Spryker\Zed\Transfer\Business\Model\Generator\Helper\PluralizerInterface
     */
    protected $pluralizer;

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\Helper\PluralizerInterface $pluralizer
     */
    public function __construct(PluralizerInterface $pluralizer)
    {
        $this->pluralizer = $pluralizer;
    }

    /**
     * @param array $transferDefinitions
     *
     * @return array
     */
    public function normalizeDefinitions(array $transferDefinitions)
    {
        $normalizedDefinitions = [];
        $filter = new UnderscoreToCamelCase();
        foreach ($transferDefinitions as $transferDefinition) {
            $transferName = $filter->filter($transferDefinition[self::KEY_NAME]) . static::KEY_ENTITY;
            $properties = $this->normalizeAttributes($transferDefinition[self::KEY_COLUMN], $transferDefinition[self::KEY_BUNDLE]);
            if (isset($transferDefinition[self::KEY_FOREIGN_KEY])) {
                $properties = $this->normalizeForeignKeys($transferDefinition[self::KEY_FOREIGN_KEY], $properties, $transferDefinition[self::KEY_BUNDLE]);
            }
            $normalizedDefinition = [
                self::KEY_BUNDLE => $transferDefinition[self::KEY_BUNDLE],
                self::KEY_CONTAINING_BUNDLE => $transferDefinition[self::KEY_CONTAINING_BUNDLE],
                self::KEY_NAME => $transferName,
                self::KEY_DEPRECATED => isset($transferDefinition[self::KEY_DEPRECATED]) ? $transferDefinition[self::KEY_DEPRECATED] : null,
                self::KEY_PROPERTY => $properties,
                self::ENTITY_NAMESPACE => isset($transferDefinition[self::KEY_PHP_NAME]) ? $transferDefinition[self::ENTITY_NAMESPACE] . '\\' . $transferDefinition[self::KEY_PHP_NAME] : null,
            ];

            $normalizedDefinitions[] = $normalizedDefinition;
        }
        $normalizedDefinitions = $this->adjustRelations($normalizedDefinitions);

        return $normalizedDefinitions;
    }

    /**
     * @param array $attributes
     * @param string $module
     *
     * @return array
     */
    protected function normalizeAttributes(array $attributes, $module)
    {
        if (isset($attributes[0])) {
            $attributes = $this->addBundleToAttributes($attributes, $module);
            $attributes = $this->addEntityDataToAttributes($attributes);

            return $attributes;
        }

        $attributes = $this->addBundleToAttributes([$attributes], $module);
        $attributes = $this->addEntityDataToAttributes($attributes);

        return $attributes;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function addEntityDataToAttributes(array $attributes)
    {
        $filter = new UnderscoreToCamelCase();
        foreach ($attributes as &$attribute) {
            $attribute[self::KEY_NAME] = lcfirst($filter->filter($attribute[self::KEY_NAME]));
            $attribute[self::KEY_TYPE] = $this->getTransferType($attribute[self::KEY_TYPE]);
        }

        return $attributes;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function getTransferType($type)
    {
        $type = mb_strtolower($type);
        if (!preg_match('/^int|^integer|^float|^string|^array|^\[\]|^bool|^boolean/', $type)) {
            return 'string';
        }

        return $type;
    }

    /**
     * @param array $foreignKeys
     * @param array $properties
     * @param string $module
     *
     * @return array
     */
    private function normalizeForeignKeys(array $foreignKeys, array $properties, $module)
    {
        if (isset($foreignKeys[0])) {
            return $this->addForeignKeyTransfer($foreignKeys, $properties, $module);
        }

        return $this->addForeignKeyTransfer([$foreignKeys], $properties, $module);
    }

    /**
     * @param array $foreignKeys
     * @param array $properties
     * @param string $module
     *
     * @return mixed
     */
    protected function addForeignKeyTransfer($foreignKeys, &$properties, $module)
    {
        $filter = new UnderscoreToCamelCase();
        foreach ($foreignKeys as &$foreignKey) {
            if (isset($foreignKey[self::KEY_PHP_NAME])) {
                $propertyName = lcfirst($foreignKey[self::KEY_PHP_NAME]);
            } else {
                $propertyName = lcfirst($filter->filter($foreignKey[self::FOREIGN_TABLE]));
            }

            $properties[] = [
                self::KEY_NAME => $propertyName,
                self::KEY_TYPE => $filter->filter($foreignKey[self::FOREIGN_TABLE]) . static::KEY_ENTITY,
                self::KEY_BUNDLE => [$module],
                self::KEY_BUNDLES => [$module],
            ];
        }

        return $properties;
    }

    /**
     * @param array $allDefinitions
     *
     * @return array
     */
    protected function adjustRelations(array $allDefinitions)
    {
        $mappedDefinitions = [];
        foreach ($allDefinitions as $normalizedDefinition) {
            $mappedDefinitions[] = $this->scanAndAddRelation($normalizedDefinition, $allDefinitions);
        }

        return $mappedDefinitions;
    }

    /**
     * @param array $normalizedDefinition
     * @param array $allDefinitions
     *
     * @return mixed
     */
    protected function scanAndAddRelation(array $normalizedDefinition, array $allDefinitions)
    {
        foreach ($allDefinitions as $definition) {
            foreach ($definition[self::KEY_PROPERTY] as $property) {
                if ($normalizedDefinition[self::KEY_NAME] === $property[self::KEY_TYPE]) {
                    $propertyName = lcfirst(str_replace(self::KEY_ENTITY, '', $definition[self::KEY_NAME]));
                    $normalizedDefinition[self::KEY_PROPERTY][] = [
                        self::KEY_NAME => $this->pluralizer->getPluralForm($propertyName),
                        self::KEY_TYPE => $definition[self::KEY_NAME] . '[]',
                        self::KEY_BUNDLES => $property[self::KEY_BUNDLE],
                    ];
                }
            }
        }
        return $normalizedDefinition;
    }
}
