<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Object;

use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

interface ObjectInterface extends SchemaFieldInterface
{
    /**
     * @return \Spryker\Glue\Testify\OpenApi3\Object\ObjectSpecification
     */
    public function getObjectSpecification(): ObjectSpecification;

    /**
     * @return $this
     */
    public function export();
}
