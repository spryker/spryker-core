<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Router;

use Codeception\Actor;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Storage\StorageConstants;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Yves\Router\PHPMD)
 */
class RouterYvesTester extends Actor
{
    use _generated\RouterYvesTesterActions;

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_SCHEME
     *
     * @var string
     */
    protected const REDIS_SCHEME = 'STORAGE_REDIS:STORAGE_REDIS_SCHEME';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_HOST
     *
     * @var string
     */
    protected const REDIS_HOST = 'STORAGE_REDIS:STORAGE_REDIS_HOST';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PORT
     *
     * @var string
     */
    protected const REDIS_PORT = 'STORAGE_REDIS:STORAGE_REDIS_PORT';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_DATABASE
     *
     * @var string
     */
    protected const REDIS_DATABASE = 'STORAGE_REDIS:STORAGE_REDIS_DATABASE';

    /**
     * @uses \Spryker\Shared\StorageRedis\StorageRedisConstants::STORAGE_REDIS_PASSWORD
     *
     * @var string
     */
    protected const REDIS_PASSWORD = 'STORAGE_REDIS:STORAGE_REDIS_PASSWORD';

    /**
     * @return void
     */
    public function setupStorageRedisConfig(): void
    {
        $this->setConfig(StorageConstants::STORAGE_REDIS_PROTOCOL, Config::get(static::REDIS_SCHEME, false));
        $this->setConfig(StorageConstants::STORAGE_REDIS_PORT, Config::get(static::REDIS_PORT));
        $this->setConfig(StorageConstants::STORAGE_REDIS_HOST, Config::get(static::REDIS_HOST));
        $this->setConfig(StorageConstants::STORAGE_REDIS_DATABASE, Config::get(static::REDIS_DATABASE));
        $this->setConfig(StorageConstants::STORAGE_REDIS_PASSWORD, Config::get(static::REDIS_PASSWORD));
    }
}
