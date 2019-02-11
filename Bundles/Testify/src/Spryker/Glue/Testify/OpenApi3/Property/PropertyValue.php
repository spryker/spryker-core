<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Property;

use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

class PropertyValue implements PropertyValueInterface
{
    /**
     * @var \Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition
     */
    protected $definition;

    /**
     * @var \Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface
     */
    protected $value;

    /**
     * @param \Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition $definition
     * @param \Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface $value
     */
    public function __construct(PropertyDefinition $definition, SchemaFieldInterface $value)
    {
        $this->definition = $definition;
        $this->value = $value;
    }

    /**
     * @return \Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition
     */
    public function getDefinition(): PropertyDefinition
    {
        return $this->definition;
    }

    /**
     * @return \Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface
     */
    public function getValue(): SchemaFieldInterface
    {
        return $this->value;
    }
}
