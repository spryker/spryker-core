<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Collection\Paths;
use Spryker\Glue\Testify\OpenApi3\Collection\SecurityRequirements;
use Spryker\Glue\Testify\OpenApi3\Collection\Servers;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringEnumeration;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string $openapi
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Info $info
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Server[] $servers
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\PathItem[] $paths
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Components $components
 * @property-read \Spryker\Glue\Testify\OpenApi3\Collection\SecurityRequirement[] $security
 * @property-read string[] $tags
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\ExternalDocumentation $externalDocs
 */
class OpenApi extends AbstractObject
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('openapi', (new PropertyDefinition(StringPrimitive::class))->setRequired(true))
            ->setProperty('info', (new PropertyDefinition(Info::class))->setRequired(true))
            ->setProperty('servers', new PropertyDefinition(Servers::class))
            ->setProperty('paths', (new PropertyDefinition(Paths::class))->setRequired(true))
            ->setProperty('components', new PropertyDefinition(Components::class))
            ->setProperty('security', new PropertyDefinition(SecurityRequirements::class))
            ->setProperty('tags', new PropertyDefinition(StringEnumeration::class))
            ->setProperty('externalDocs', new PropertyDefinition(ExternalDocumentation::class));
    }
}
