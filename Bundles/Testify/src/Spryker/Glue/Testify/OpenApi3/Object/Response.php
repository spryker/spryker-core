<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Collection\Headers;
use Spryker\Glue\Testify\OpenApi3\Collection\Links;
use Spryker\Glue\Testify\OpenApi3\Collection\MediaTypes;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;

/**
 * @property-read string $description
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Header[] $headers
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\MediaType[] $content
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Link[] $links
 * @property-read bool $required
 */
class Response extends AbstractObject implements ReferableInterface
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('description', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('headers', new PropertyDefinition(Headers::class))
            ->setProperty('content', new PropertyDefinition(MediaTypes::class))
            ->setProperty('links', new PropertyDefinition(Links::class));
    }
}
