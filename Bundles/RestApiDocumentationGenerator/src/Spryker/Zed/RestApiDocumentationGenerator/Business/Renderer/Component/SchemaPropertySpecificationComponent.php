<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component;

class SchemaPropertySpecificationComponent implements SpecificationComponentInterface
{
    protected const KEY_TYPE = 'type';
    protected const KEY_REF = '$ref';
    protected const KEY_ITEMS = 'items';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $schemaReference;

    /**
     * @var string
     */
    protected $itemsSchemaReference;

    /**
     * @var string
     */
    protected $itemsType;

    /**
     * @return array
     */
    public function toArray(): array
    {
        $property = [];
        if ($this->type) {
            $property[static::KEY_TYPE] = $this->type;
        }
        if ($this->schemaReference) {
            $property[static::KEY_REF] = $this->schemaReference;
        }
        if ($this->itemsSchemaReference) {
            $property[static::KEY_ITEMS][static::KEY_REF] = $this->itemsSchemaReference;
        }

        return [$this->name => $property];
    }

    /**
     * @return array
     */
    public function getRequiredProperties(): array
    {
        return [
            $this->name,
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
     * @param string $type
     *
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string $schemaReference
     *
     * @return void
     */
    public function setSchemaReference(string $schemaReference): void
    {
        $this->schemaReference = $schemaReference;
    }

    /**
     * @param string $itemsSchemaReference
     *
     * @return void
     */
    public function setItemsSchemaReference(string $itemsSchemaReference): void
    {
        $this->itemsSchemaReference = $itemsSchemaReference;
    }

    /**
     * @param string $itemsType
     *
     * @return void
     */
    public function setItemsType(string $itemsType): void
    {
        $this->itemsType = $itemsType;
    }
}
