<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Definition;

use Spryker\Zed\Search\Business\Exception\MissingNameAttributeException;

class IndexDefinition
{

    const NAME = 'name';
    const SETTINGS = 'settings';
    const MAPPING = 'mapping';
    const PROPERTY = 'property';
    const PROPERTIES = 'properties';
    const ANALYSIS = 'analysis';
    const ANALYZER = 'analyzer';
    const CHAR_FILTER = 'char_filter';
    const FILTER = 'filter';
    const TOKENIZER = 'tokenizer';

    /**
     * @var array
     */
    protected $definition;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var array
     */
    protected $mappings = [];

    /**
     * @param array $definitionData
     */
    public function __construct(array $definitionData)
    {
        $this->definition = $definitionData;

        $this->processDefinition();
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
        return $this->settings;
    }

    /**
     * @return array
     */
    public function getMappings()
    {
        return $this->mappings;
    }

    /**
     * @return void
     */
    protected function processDefinition()
    {
        $this->processSettings();
        $this->processMappingDefinitions();
    }

    /**
     * @return void
     */
    protected function processSettings()
    {
        if (!isset($this->definition[self::SETTINGS])) {
            return;
        }

        $this->settings = $this->definition[self::SETTINGS];

        if (isset($this->settings[self::ANALYSIS])) {
            foreach ($this->settings[self::ANALYSIS] as $settingKey => $settingValue) {
                if ($this->isAssociativeArray($settingValue)) {
                    $this->settings[self::ANALYSIS][$settingKey] = $this->normalizeSetting([$settingValue]);
                } else {
                    $this->settings[self::ANALYSIS][$settingKey] = $this->normalizeSetting($settingValue);
                }
            }
        }
    }

    /**
     * @param array $settings
     *
     * @return array
     */
    protected function normalizeSetting(array $settings)
    {
        $normalizedSetting = [];

        foreach ($settings as $setting) {
            $this->assertNameExists($setting);

            $settingName = $setting[self::NAME];
            unset($setting[self::NAME]);
            $normalizedSetting[$settingName] = $setting;
        }

        return $normalizedSetting;
    }

    /**
     * @return void
     */
    protected function processMappingDefinitions()
    {
        if (!isset($this->definition[self::MAPPING])) {
            return;
        }

        $mappingStack = $this->definition[self::MAPPING];

        if ($this->isAssociativeArray($mappingStack)) {
            $this->addMapping($mappingStack);
            return;
        }

        foreach ($mappingStack as $mapping) {
            $this->addMapping($mapping);
        }
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
     * @param array $mapping
     *
     * @return array
     */
    protected function addMapping(array $mapping)
    {
        $this->assertNameExists($mapping);

        $normalizedMapping = [];
        if (isset($mapping[self::PROPERTY])) {
            if ($this->isAssociativeArray($mapping[self::PROPERTY])) {
                $normalizedMapping = $this->normalizeMappingProperties([$mapping[self::PROPERTY]]);
            } else {
                $normalizedMapping = $this->normalizeMappingProperties($mapping[self::PROPERTY]);
            }
        }

        $this->mappings[$mapping[self::NAME]] = $normalizedMapping;
    }

    /**
     * @param array $properties
     *
     * @return array
     */
    protected function normalizeMappingProperties(array $properties)
    {
        $normalizedProperties = [];

        foreach ($properties as $property) {
            $this->assertNameExists($property);

            $propertyName = $property[self::NAME];
            unset($property[self::NAME]);

            if (isset($property[self::PROPERTIES][self::PROPERTY])) {
                if ($this->isAssociativeArray($property[self::PROPERTIES][self::PROPERTY])) {
                    $property[self::PROPERTIES] = $this->normalizeMappingProperties([$property[self::PROPERTIES][self::PROPERTY]]);
                } else {
                    $property[self::PROPERTIES] = $this->normalizeMappingProperties($property[self::PROPERTIES][self::PROPERTY]);
                }
            }

            $normalizedProperties[$propertyName] = $property;
        }

        return $normalizedProperties;
    }

    /**
     * @param array $data
     *
     * @throws \Spryker\Zed\Search\Business\Exception\MissingNameAttributeException
     *
     * @return void
     */
    protected function assertNameExists(array $data)
    {
        $hasName = isset($data[self::NAME]);

        if (!$hasName) {
            throw new MissingNameAttributeException(sprintf(
                'Missing "name" attribute from %s',
                print_r($data, true)
            ));
        }
    }

}
