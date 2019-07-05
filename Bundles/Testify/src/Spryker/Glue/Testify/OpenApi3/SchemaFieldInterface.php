<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3;

interface SchemaFieldInterface
{
    /**
     * @param mixed $content
     *
     * @return $this
     */
    public function hydrate($content);

    /**
     * @return mixed
     */
    public function export();
}
