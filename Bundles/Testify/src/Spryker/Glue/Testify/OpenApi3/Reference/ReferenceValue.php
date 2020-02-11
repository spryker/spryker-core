<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\Reference;

use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface;
use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

class ReferenceValue implements PropertyValueInterface
{
    /**
     * @var \Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition
     */
    protected $definition;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var \Spryker\Glue\Testify\OpenApi3\Reference\ReferenceResolverInterface
     */
    protected $resolver;

    /**
     * @param \Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition $definition
     * @param string $reference
     * @param \Spryker\Glue\Testify\OpenApi3\Reference\ReferenceResolverInterface $resolver
     */
    public function __construct(
        PropertyDefinition $definition,
        string $reference,
        ReferenceResolverInterface $resolver
    ) {
        $this->definition = $definition;
        $this->reference = $reference;
        $this->resolver = $resolver;
    }

    /**
     * @return \Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition
     */
    public function getDefinition(): PropertyDefinition
    {
        return $this->definition;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @return \Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface
     */
    public function getValue(): SchemaFieldInterface
    {
        $value = $this->resolver->resolveReference($this->reference);

        if (get_class($value) !== $this->getDefinition()->getType()) {
            trigger_error(
                sprintf(
                    'Expected reference to be %s, but %s encountered',
                    $this->getDefinition()->getType(),
                    get_class($value)
                ),
                E_USER_WARNING
            );
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function __debugInfo()
    {
        return [
            'definition' => $this->getDefinition(),
            'reference' => $this->getReference(),
        ];
    }
}
