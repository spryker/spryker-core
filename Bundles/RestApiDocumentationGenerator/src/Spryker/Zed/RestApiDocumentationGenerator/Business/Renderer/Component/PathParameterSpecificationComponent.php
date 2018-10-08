<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component;

class PathParameterSpecificationComponent implements SpecificationComponentInterface
{
    protected const KEY_DESCRIPTION = 'description';
    protected const KEY_IN = 'in';
    protected const KEY_NAME = 'name';
    protected const KEY_REQUIRED = 'required';
    protected const KEY_SCHEMA = 'schema';
    protected const KEY_TYPE = 'type';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $in;

    /**
     * @var bool
     */
    protected $required = true;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $schemaType;

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        $result[static::KEY_NAME] = $this->name;
        $result[static::KEY_IN] = $this->in;
        $result[static::KEY_REQUIRED] = $this->required;
        if ($this->description) {
            $result[static::KEY_DESCRIPTION] = $this->description;
        }
        $result[static::KEY_SCHEMA] = [
            static::KEY_TYPE => $this->schemaType,
        ];

        return $result;
    }

    /**
     * @return array
     */
    public function getRequiredProperties(): array
    {
        return [
            $this->name,
            $this->in,
            $this->required,
            $this->schemaType,
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
     * @param string $in
     *
     * @return void
     */
    public function setIn(string $in): void
    {
        $this->in = $in;
    }

    /**
     * @param bool $required
     *
     * @return void
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    /**
     * @param string $description
     *
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $schemaType
     *
     * @return void
     */
    public function setSchemaType(string $schemaType): void
    {
        $this->schemaType = $schemaType;
    }
}
