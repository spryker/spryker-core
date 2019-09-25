<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Collection;

use Spryker\Glue\Testify\OpenApi3\Object\SecurityScheme;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

class SecuritySchemes extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    public function getElementDefinition(): PropertyDefinition
    {
        return new PropertyDefinition(SecurityScheme::class);
    }
}
