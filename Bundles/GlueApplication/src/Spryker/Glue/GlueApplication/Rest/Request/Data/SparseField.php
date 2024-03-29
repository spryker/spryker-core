<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

/**
 * @deprecated Will be removed without replacement.
 */
class SparseField implements SparseFieldInterface
{
    /**
     * @var string
     */
    protected $resource;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param string $resource
     * @param array $attributes
     */
    public function __construct(string $resource, array $attributes)
    {
        $this->resource = $resource;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
