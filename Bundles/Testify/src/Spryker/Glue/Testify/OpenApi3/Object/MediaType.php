<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Collection\Encodings;
use Spryker\Glue\Testify\OpenApi3\Collection\Examples;
use Spryker\Glue\Testify\OpenApi3\Primitive\Any;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Schema $schema
 * @property-read mixed $example
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Example[] $examples
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Encoding[] $encoding
 */
class MediaType extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('schema', new PropertyDefinition(Schema::class))
            ->setProperty('example', new PropertyDefinition(Any::class))
            ->setProperty('examples', new PropertyDefinition(Examples::class))
            ->setProperty('encoding', new PropertyDefinition(Encodings::class));
    }
}
