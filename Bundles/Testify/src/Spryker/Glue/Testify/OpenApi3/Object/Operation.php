<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Collection\Callbacks;
use Spryker\Glue\Testify\OpenApi3\Collection\Parameters;
use Spryker\Glue\Testify\OpenApi3\Collection\Responses;
use Spryker\Glue\Testify\OpenApi3\Collection\SecurityRequirements;
use Spryker\Glue\Testify\OpenApi3\Collection\Servers;
use Spryker\Glue\Testify\OpenApi3\Primitive\BoolPrimitive;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringEnumeration;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

/**
 * @property-read string[] $tags
 * @property-read string $summary
 * @property-read string $description
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\ExternalDocumentation $externalDocs
 * @property-read string $operationId
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Parameter[] $parameters
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\RequestBody $requestBody
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Response[] $responses
 * @property-read \Spryker\Glue\Testify\OpenApi3\Collection\Callback[] $callbacks
 * @property-read bool $deprecated
 * @property-read \Spryker\Glue\Testify\OpenApi3\Collection\SecurityRequirements $security
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Server[] $servers
 */
class Operation extends AbstractObject
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('tags', new PropertyDefinition(StringEnumeration::class))
            ->setProperty('summary', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('externalDocs', new PropertyDefinition(ExternalDocumentation::class))
            ->setProperty('operationId', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('parameters', new PropertyDefinition(Parameters::class))
            ->setProperty('requestBody', new PropertyDefinition(RequestBody::class))
            ->setProperty('responses', (new PropertyDefinition(Responses::class))->setRequired(true))
            ->setProperty('callbacks', new PropertyDefinition(Callbacks::class))
            ->setProperty('deprecated', new PropertyDefinition(BoolPrimitive::class))
            ->setProperty('security', new PropertyDefinition(SecurityRequirements::class))
            ->setProperty('servers', new PropertyDefinition(Servers::class));
    }
}
