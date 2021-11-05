<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Laminas\Filter\Word\UnderscoreToCamelCase;
use Spryker\Zed\Transfer\Business\Model\Generator\Helper\PluralizerInterface;

class EntityDefinitionNormalizer extends DefinitionNormalizer
{
    /**
     * @var array<string, string>
     */
    protected const TYPE_MAPPING = [
        'double' => 'float',
    ];

    /**
     * @var string
     */
    public const KEY_TYPE = 'type';

    /**
     * @var string
     */
    public const KEY_COLUMN = 'column';

    /**
     * @var string
     */
    public const KEY_FOREIGN_KEY = 'foreign-key';

    /**
     * @var string
     */
    public const KEY_ENTITY = 'Entity';

    /**
     * @var string
     */
    public const FOREIGN_TABLE = 'foreignTable';

    /**
     * @var string
     */
    public const KEY_PHP_NAME = 'phpName';

    /**
     * @var string
     */
    public const ENTITY_NAMESPACE = 'entity-namespace';

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
     * @param array<array> $transferDefinitions
     *
     * @return array<array>
     */
    public function normalizeDefinitions(array $transferDefinitions)
    {
        $normalizedDefinitions = [];
        $filter = new UnderscoreToCamelCase();
        foreach ($transferDefinitions as $transferDefinition) {
            $transferName = $filter->filter($transferDefinition[static::KEY_NAME]) . static::KEY_ENTITY;
            $properties = $this->normalizeAttributes($transferDefinition[static::KEY_COLUMN], $transferDefinition[static::KEY_BUNDLE]);
            if (isset($transferDefinition[static::KEY_FOREIGN_KEY])) {
                $properties = $this->normalizeForeignKeys($transferDefinition[static::KEY_FOREIGN_KEY], $properties, $transferDefinition[static::KEY_BUNDLE]);
            }
            $normalizedDefinition = [
                static::KEY_BUNDLE => $transferDefinition[static::KEY_BUNDLE],
                static::KEY_CONTAINING_BUNDLE => $transferDefinition[static::KEY_CONTAINING_BUNDLE],
                static::KEY_NAME => $transferName,
                static::KEY_DEPRECATED => $transferDefinition[static::KEY_DEPRECATED] ?? null,
                static::KEY_PROPERTY => $properties,
                static::ENTITY_NAMESPACE => $this->findEntityNamespace($transferDefinition),
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
            $attribute[static::KEY_NAME] = lcfirst($filter->filter($attribute[static::KEY_NAME]));
            $attribute[static::KEY_TYPE] = $this->getTransferType($attribute[static::KEY_TYPE]);
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
        if (!preg_match('/^(int|integer|float|double|decimal|string|array|\[\]|bool|boolean)$/', $type)) {
            return 'string';
        }

        return static::TYPE_MAPPING[$type] ?? $type;
    }

    /**
     * @param array $foreignKeys
     * @param array $properties
     * @param string $module
     *
     * @return array
     */
    protected function normalizeForeignKeys(array $foreignKeys, array $properties, $module)
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
            if (isset($foreignKey[static::KEY_PHP_NAME])) {
                $propertyName = lcfirst($foreignKey[static::KEY_PHP_NAME]);
            } else {
                $propertyName = lcfirst($filter->filter($foreignKey[static::FOREIGN_TABLE]));
            }

            $properties[] = [
                static::KEY_NAME => $propertyName,
                static::KEY_TYPE => $filter->filter($foreignKey[static::FOREIGN_TABLE]) . static::KEY_ENTITY,
                static::KEY_BUNDLE => [$module],
                static::KEY_BUNDLES => [$module],
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
     * @param array<string, mixed> $normalizedDefinition
     * @param array<array> $allDefinitions
     *
     * @return mixed
     */
    protected function scanAndAddRelation(array $normalizedDefinition, array $allDefinitions)
    {
        foreach ($allDefinitions as $definition) {
            foreach ($definition[static::KEY_PROPERTY] as $property) {
                if ($normalizedDefinition[static::KEY_NAME] === $property[static::KEY_TYPE]) {
                    $propertyName = lcfirst(str_replace(static::KEY_ENTITY, '', $definition[static::KEY_NAME]));
                    $normalizedDefinition[static::KEY_PROPERTY][] = [
                        static::KEY_NAME => $this->pluralizer->getPluralForm($propertyName),
                        static::KEY_TYPE => $definition[static::KEY_NAME] . '[]',
                        static::KEY_BUNDLES => $property[static::KEY_BUNDLE],
                    ];
                }
            }
        }

        return $normalizedDefinition;
    }

    /**
     * @param array<string, mixed> $transferDefinition
     *
     * @return string|null
     */
    protected function findEntityNamespace(array $transferDefinition)
    {
        if (isset($transferDefinition[static::KEY_PHP_NAME])) {
            return $transferDefinition[static::ENTITY_NAMESPACE] . '\\' . $transferDefinition[static::KEY_PHP_NAME];
        }

        if (isset($transferDefinition[static::KEY_NAME])) {
            $entityName = str_replace('_', '', ucwords($transferDefinition[static::KEY_NAME], '_'));

            return $transferDefinition[static::ENTITY_NAMESPACE] . '\\' . $entityName;
        }

        return null;
    }
}
