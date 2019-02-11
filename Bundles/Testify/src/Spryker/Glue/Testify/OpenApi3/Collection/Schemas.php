<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Collection;

use Spryker\Glue\Testify\OpenApi3\Object\Schema;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

class Schemas extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    public function getElementDefinition(): PropertyDefinition
    {
        return new PropertyDefinition(Schema::class);
    }
}
