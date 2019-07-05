<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\Helper;

use SprykerTest\Glue\Testify\Model\Connection;

interface LastConnectionProviderInterface
{
    /**
     * @return \SprykerTest\Glue\Testify\Model\Connection|null
     */
    public function getLastConnection(): ?Connection;
}
