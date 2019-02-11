<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Collection;

use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

interface CollectionInterface extends SchemaFieldInterface
{
    /**
     * @return \Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition
     */
    public function getElementDefinition(): PropertyDefinition;

    /**
     * @return \Spryker\Glue\Testify\OpenApi3\Collection\CollectionInterface
     */
    public function export(): self;

    /**
     * @return array
     */
    public function toArray(): array;
}
