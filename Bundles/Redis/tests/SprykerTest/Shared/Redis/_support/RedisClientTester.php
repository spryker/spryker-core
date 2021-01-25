<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Redis;

use Codeception\Actor;
use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Generated\Shared\Transfer\RedisCredentialsTransfer;
use Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceBridge;
use Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface;
use Spryker\Shared\Redis\Logger\RedisInMemoryLogger;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class RedisClientTester extends Actor
{
    use _generated\RedisClientTesterActions;

    public const DEFAULT_REDIS_PROTOCOL = 'redis';
    public const DEFAULT_REDIS_HOST = 'localhost';
    public const DEFAULT_REDIS_PORT = 6379;
    public const DEFAULT_REDIS_DATABASE = 1;

    /**
     * @var \Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface
     */
    protected $redisToUtilEncodingServiceBridge;

    /**
     * @param string $protocol
     * @param string $host
     * @param int $port
     * @param string $database
     *
     * @return \Spryker\Shared\Redis\Logger\RedisInMemoryLogger
     */
    public function createRedisInMemoryLogger(
        string $protocol = self::DEFAULT_REDIS_PROTOCOL,
        string $host = self::DEFAULT_REDIS_HOST,
        int $port = self::DEFAULT_REDIS_PORT,
        string $database = self::DEFAULT_REDIS_DATABASE
    ): RedisInMemoryLogger {
        $connectionCredentials = (new RedisCredentialsTransfer())
            ->setProtocol($protocol)
            ->setHost($host)
            ->setPort($port)
            ->setDatabase($database);
        $configurationTransfer = (new RedisConfigurationTransfer())
            ->setConnectionCredentials($connectionCredentials);

        return new RedisInMemoryLogger(
            $this->getUtilEncodingService(),
            $configurationTransfer
        );
    }

    /**
     * @param mixed $data
     * @param int|null $options
     *
     * @return string
     */
    public function encodeJson($data, ?int $options = null): string
    {
        return $this->getUtilEncodingService()->encodeJson($data, $options);
    }

    /**
     * @return \Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): RedisToUtilEncodingServiceInterface
    {
        if (!$this->redisToUtilEncodingServiceBridge) {
            $this->redisToUtilEncodingServiceBridge = new RedisToUtilEncodingServiceBridge(
                $this->getLocator()->utilEncoding()->service()
            );
        }

        return $this->redisToUtilEncodingServiceBridge;
    }
}
