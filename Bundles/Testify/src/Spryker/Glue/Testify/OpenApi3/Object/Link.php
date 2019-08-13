<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\Primitive\Any;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;

/**
 * @property-read string $operationRef
 * @property-read string $operationId
 * @property-read array $parameters
 * @property-read mixed $requestBody
 * @property-read string $description
 * @property-read \Spryker\Glue\Testify\OpenApi3\Object\Server $server
 */
class Link extends AbstractObject implements ReferableInterface
{
    /**
     * @inheritdoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('operationRef', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('operationId', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('parameters', new PropertyDefinition(Any::class))
            ->setProperty('requestBody', new PropertyDefinition(Any::class))
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('server', new PropertyDefinition(Server::class));
    }
}
