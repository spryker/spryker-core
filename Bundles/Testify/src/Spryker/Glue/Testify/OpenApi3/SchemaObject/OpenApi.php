<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Collection\Paths;
use Spryker\Glue\Testify\OpenApi3\Collection\SecurityRequirements;
use Spryker\Glue\Testify\OpenApi3\Collection\Servers;
use Spryker\Glue\Testify\OpenApi3\Collection\Tags;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string $openapi
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Info $info
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Server[] $servers
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\PathItem[] $paths
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Components $components
 * @property-read \Spryker\Glue\Testify\OpenApi3\Collection\SecurityRequirement[] $security
 * @property-read string[] $tags
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\ExternalDocumentation $externalDocs
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
            ->setProperty('tags', new PropertyDefinition(Tags::class))
            ->setProperty('externalDocs', new PropertyDefinition(ExternalDocumentation::class));
    }
}
