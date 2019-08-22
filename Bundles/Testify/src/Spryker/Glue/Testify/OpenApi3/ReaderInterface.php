<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3;

interface ReaderInterface
{
    /**
     * @throws \Spryker\Glue\Testify\OpenApi3\Exception\ParseException
     *
     * @return object
     */
    public function read();
}
