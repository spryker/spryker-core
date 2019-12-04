<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string $title
 * @property-read string $description
 * @property-read string $termsOfService
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Contact $contact
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\License $license
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
