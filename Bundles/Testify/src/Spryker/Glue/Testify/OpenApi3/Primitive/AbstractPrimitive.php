<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\Primitive;

use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

abstract class AbstractPrimitive implements PrimitiveInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    abstract protected function cast($value);

    /**
     * @inheritDoc
     */
    public function hydrate($content): SchemaFieldInterface
    {
        $this->value = $this->cast($content);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function export()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return (string)$this->export();
    }
}
