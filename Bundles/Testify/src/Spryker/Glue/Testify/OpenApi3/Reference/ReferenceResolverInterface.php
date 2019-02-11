<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Reference;

use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

interface ReferenceResolverInterface
{
    /**
     * @param string $reference
     *
     * @return \Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface
     */
    public function resolveReference(string $reference): SchemaFieldInterface;
}
