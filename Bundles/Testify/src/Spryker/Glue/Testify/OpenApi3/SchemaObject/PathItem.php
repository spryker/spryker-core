<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Collection\Parameters;
use Spryker\Glue\Testify\OpenApi3\Collection\Servers;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;

/**
 * @property-read string $summary
 * @property-read string $description
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Operation $get
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Operation $put
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Operation $post
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Operation $delete
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Operation $options
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Operation $head
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Operation $patch
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Operation $trace
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Server[] $servers
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\Parameter[] $parameters
 *
 * @property-read \Spryker\Glue\Testify\OpenApi3\SchemaObject\License $license
 * @property-read string $version
 */
class PathItem extends AbstractObject implements ReferableInterface
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('summary', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('get', new PropertyDefinition(Operation::class))
            ->setProperty('put', new PropertyDefinition(Operation::class))
            ->setProperty('post', new PropertyDefinition(Operation::class))
            ->setProperty('delete', new PropertyDefinition(Operation::class))
            ->setProperty('options', new PropertyDefinition(Operation::class))
            ->setProperty('head', new PropertyDefinition(Operation::class))
            ->setProperty('patch', new PropertyDefinition(Operation::class))
            ->setProperty('trace', new PropertyDefinition(Operation::class))
            ->setProperty('servers', new PropertyDefinition(Servers::class))
            ->setProperty('parameters', new PropertyDefinition(Parameters::class));
    }
}
