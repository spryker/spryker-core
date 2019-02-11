<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;

/**
 * @property-read string $type
 * @property-read string $description
 * @property-read string $name
 * @property-read string $in
 * @property-read string $scheme
 * @property-read string $bearerFormat
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\OAuthFlows $flows
 * @property-read string $openIdConnectUrl
 */
class SecurityScheme extends AbstractObject implements ReferableInterface
{
    /**
     * @inheritdoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('type', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('name', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('in', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('scheme', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('bearerFormat', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('flows', (new PropertyDefinition(OAuthFlows::class))->setRequired(true))
            ->setProperty('openIdConnectUrl', (new PropertyDefinition(StringPrimitive::class))->setRequired(true));
    }
}
