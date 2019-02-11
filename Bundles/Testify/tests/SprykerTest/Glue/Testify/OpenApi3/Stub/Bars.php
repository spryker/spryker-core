<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\OpenApi3\Stub;

use Spryker\Glue\Testify\OpenApi3\Collection\AbstractCollection;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

class Bars extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    public function getElementDefinition(): PropertyDefinition
    {
        return new PropertyDefinition(Bar::class);
    }
}
