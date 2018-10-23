<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component;

class PathRequestSpecificationComponent implements SpecificationComponentInterface
{
    protected const KEY_APPLICATION_JSON = 'application/json';
    protected const KEY_CONTENT = 'content';
    protected const KEY_DESCRIPTION = 'description';
    protected const KEY_REF = '$ref';
    protected const KEY_REQUIRED = 'required';
    protected const KEY_SCHEMA = 'schema';

    /**
     * @var string
     */
    protected $description;

    /**
     * @var bool
     */
    protected $required = true;

    /**
     * @var string
     */
    protected $jsonSchemaRef;

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        $result[static::KEY_DESCRIPTION] = $this->description;
        $result[static::KEY_REQUIRED] = $this->required;
        if ($this->jsonSchemaRef) {
            $result[static::KEY_CONTENT][static::KEY_APPLICATION_JSON][static::KEY_SCHEMA][static::KEY_REF] = $this->jsonSchemaRef;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getRequiredProperties(): array
    {
        return [
            $this->description,
            $this->required,
            $this->jsonSchemaRef,
        ];
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
     * @param bool $required
     *
     * @return void
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    /**
     * @param string $jsonSchemaRef
     *
     * @return void
     */
    public function setJsonSchemaRef(string $jsonSchemaRef): void
    {
        $this->jsonSchemaRef = $jsonSchemaRef;
    }
}
