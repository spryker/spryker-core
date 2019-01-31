<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\StorageDatabase\ConnectionProvider\ConnectionProviderInterface;

class StorageDatabaseFactory extends AbstractFactory
{
    /**
     * @return void
     */
    public function createConnectionProvider(): ConnectionProviderInterface
    {
        // provide implementation
    }
}
