<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\StorageDatabase;

use Codeception\Actor;
use Spryker\Client\StorageDatabase\Plugin\PostgreSqlStorageReaderPlugin;
use Spryker\Client\StorageDatabase\StorageDatabaseDependencyProvider;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class StorageDatabaseClientTester extends Actor
{
    use _generated\StorageDatabaseClientTesterActions;

    /**
     * @return void
     */
    public function setupStorageReaderPlugins(): void
    {
        $this->setDependency(StorageDatabaseDependencyProvider::PLUGIN_STORAGE_READER_PROVIDER, new PostgreSqlStorageReaderPlugin());
    }
}
