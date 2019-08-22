<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\OpenApi3\Stub;

use Spryker\Glue\Testify\OpenApi3\Object\AbstractObject;
use Spryker\Glue\Testify\OpenApi3\Object\ObjectSpecification;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;

/**
 * @property-read \SprykerTest\Glue\Testify\OpenApi3\Stub\Bars $bar
 */
class Foo extends AbstractObject implements ReferableInterface
{
    /**
     * @inheritdoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('bar', new PropertyDefinition(Bars::class));
    }
}
