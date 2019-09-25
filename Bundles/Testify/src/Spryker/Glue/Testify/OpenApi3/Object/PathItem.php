<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Collection\Parameters;
use Spryker\Glue\Testify\OpenApi3\Collection\Servers;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;

/**
 * @property-read string $summary
 * @property-read string $description
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Operation $get
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Operation $put
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Operation $post
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Operation $delete
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Operation $options
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Operation $head
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Operation $patch
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Operation $trace
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Server[] $servers
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Parameter[] $parameters
 *
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\License $license
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
