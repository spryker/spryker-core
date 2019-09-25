<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string $title
 * @property-read string $description
 * @property-read string $termsOfService
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Contact $contact
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\License $license
 * @property-read string $version
 */
class Info extends AbstractObject
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('title', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('termsOfService', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('contact', new PropertyDefinition(Contact::class))
            ->setProperty('license', new PropertyDefinition(License::class))
            ->setProperty('version', new PropertyDefinition(StringPrimitive::class));
    }
}
