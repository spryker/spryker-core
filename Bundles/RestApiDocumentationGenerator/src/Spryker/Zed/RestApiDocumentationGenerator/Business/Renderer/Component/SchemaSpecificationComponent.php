<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component;

class SchemaSpecificationComponent implements SpecificationComponentInterface
{
    protected const KEY_PROPERTIES = 'properties';
    protected const KEY_REQUIRED = 'required';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SchemaPropertySpecificationComponent[]
     */
    protected $properties;

    /**
     * @var string[]
     */
    protected $required;

    /**
     * @return array
     */
    public function toArray(): array
    {
        $properties = [];
        foreach ($this->properties as $property) {
            $properties += $property->toArray();
        }
        $schemaData[$this->name][static::KEY_PROPERTIES] = $properties;
        if ($this->required) {
            $schemaData[$this->name][static::KEY_REQUIRED] = $this->required;
        }

        return $schemaData;
    }

    /**
     * @return array
     */
    public function getRequiredProperties(): array
    {
        return [
            $this->name,
            $this->properties,
        ];
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SchemaPropertySpecificationComponent[] $properties
     *
     * @return void
     */
    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\SchemaPropertySpecificationComponent $schemaPropertyComponent
     *
     * @return void
     */
    public function addProperty(SchemaPropertySpecificationComponent $schemaPropertyComponent): void
    {
        $this->properties[] = $schemaPropertyComponent;
    }

    /**
     * @param string[] $required
     *
     * @return void
     */
    public function setRequired(array $required): void
    {
        $this->required = $required;
    }

    /**
     * @param string $required
     *
     * @return void
     */
    public function addRequired(string $required): void
    {
        $this->required[] = $required;
    }
}
