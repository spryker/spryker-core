<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * @inheritdoc
     */
    public function hydrate($content): SchemaFieldInterface
    {
        $this->value = $this->cast($content);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return (string)$this->export();
    }
}
