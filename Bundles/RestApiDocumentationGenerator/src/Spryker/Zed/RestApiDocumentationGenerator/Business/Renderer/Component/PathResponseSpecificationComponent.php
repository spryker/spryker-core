<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component;

class PathResponseSpecificationComponent implements SpecificationComponentInterface
{
    protected const KEY_APPLICATION_JSON = 'application/json';
    protected const KEY_CONTENT = 'content';
    protected const KEY_DESCRIPTION = 'description';
    protected const KEY_REF = '$ref';
    protected const KEY_SCHEMA = 'schema';

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $jsonSchemaRef;

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result[static::KEY_DESCRIPTION] = $this->description;
        if ($this->jsonSchemaRef) {
            $result[static::KEY_CONTENT][static::KEY_APPLICATION_JSON][static::KEY_SCHEMA][static::KEY_REF] = $this->jsonSchemaRef;
        }

        return [$this->code => $result];
    }

    /**
     * @return array
     */
    public function getRequiredProperties(): array
    {
        return [
            $this->code,
            $this->description,
        ];
    }

    /**
     * @param string $code
     *
     * @return void
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
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
     * @param string $jsonSchemaRef
     *
     * @return void
     */
    public function setJsonSchemaRef(string $jsonSchemaRef): void
    {
        $this->jsonSchemaRef = $jsonSchemaRef;
    }
}
