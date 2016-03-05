<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Spryker\Zed\Search\Business\Exception\InvalidMappingPropertyFormatException;
use Spryker\Zed\Search\Business\Exception\InvalidMappingTypeFormatException;

class IndexDefinition
{
    const NAME = 'name';
    const SETTINGS = 'settings';
    const MAPPING_TYPES = 'mapping_types';
    const MAPPING_TYPE = 'mapping_type';
    const MAPPING = 'mapping';
    const PROPERTY = 'property';
    const PROPERTIES = 'properties';

    /**
     * @var array
     */
    protected $definition;

    /**
     * @var array
     */
    protected $mappingTypes = [];

    /**
     * @param array $definitionData
     */
    public function __construct(array $definitionData)
    {
        $this->definition = $definitionData;

        $this->processMappingTypes();
    }

    /**
     * @return array
     */
    public function getIndexName()
    {
        return $this->definition[self::NAME];
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->definition[self::SETTINGS];
    }

    /**
     * @return array
     */
    public function getMappingTypes()
    {
        return $this->mappingTypes;
    }

    /**
     * @return void
     */
    protected function processMappingTypes()
    {
        if (!isset($this->definition[self::MAPPING_TYPES][self::MAPPING_TYPE])) {
            return;
        }

        $mappingTypeStack = $this->definition[self::MAPPING_TYPES][self::MAPPING_TYPE];

        if ($this->isAssociativeArray($mappingTypeStack)) {
            $this->mappingTypes = $this->normalizeMappingType($mappingTypeStack);
            return;
        }

        foreach ($mappingTypeStack as $i => $mappingType) {
            $mappingTypeStack[$i] = $this->normalizeMappingType($mappingType);
        }

        $this->mappingTypes = $mappingTypeStack;
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    protected function isAssociativeArray(array $array)
    {
        return array_values($array) !== $array;
    }

    /**
     * @param array $mappingType
     *
     * @return array
     */
    protected function normalizeMappingType(array $mappingType)
    {
        $this->assertMappingType($mappingType);

        if (isset($mappingType[self::MAPPING][self::PROPERTY])) {
            if ($this->isAssociativeArray($mappingType[self::MAPPING][self::PROPERTY])) {
                $mappingType[self::MAPPING] = $this->normalizeMappingTypeProperties([$mappingType[self::MAPPING][self::PROPERTY]]);
            } else {
                $mappingType[self::MAPPING] = $this->normalizeMappingTypeProperties($mappingType[self::MAPPING][self::PROPERTY]);
            }
        }

        return $mappingType;
    }

    /**
     * @param array $properties
     *
     * @return array
     */
    protected function normalizeMappingTypeProperties(array $properties)
    {
        $normalizedProperties = [];

        foreach ($properties as $property) {
            $this->assertProperty($property);

            $propertyName = $property[self::NAME];
            unset($property[self::NAME]);

            if (isset($property[self::PROPERTIES])) {
                if ($this->isAssociativeArray($property[self::PROPERTIES][self::PROPERTY])) {
                    $property[self::PROPERTIES] = $this->normalizeMappingTypeProperties([$property[self::PROPERTIES][self::PROPERTY]]);
                    continue;
                }

                $property[self::PROPERTIES] = $this->normalizeMappingTypeProperties($property[self::PROPERTIES][self::PROPERTY]);
            }

            $normalizedProperties[$propertyName] = $property;
        }

        return $normalizedProperties;
    }

    /**
     * @param array $mappingType
     *
     * @throws \Spryker\Zed\Search\Business\Exception\InvalidMappingTypeFormatException
     *
     * @return void
     */
    protected function assertMappingType(array $mappingType)
    {
        $hasName = isset($mappingType[self::NAME]);
        $hasMapping = isset($mappingType[self::MAPPING]);

        if (!$hasName || !$hasMapping) {
            throw new InvalidMappingTypeFormatException(sprintf(
                'Mapping type format is wrong in: %s. Expected "name", "mapping".',
                print_r($mappingType, true)
            ));
        }
    }

    /**
     * @param array $property
     *
     * @throws \Spryker\Zed\Search\Business\Exception\InvalidMappingPropertyFormatException
     *
     * @return void
     */
    protected function assertProperty(array $property)
    {
        $hasName = isset($property[self::NAME]);

        if (!$hasName) {
            throw new InvalidMappingPropertyFormatException(sprintf(
                'Mapping property format is wrong in: %s. Expected "name".',
                print_r($property, true)
            ));
        }
    }

}
