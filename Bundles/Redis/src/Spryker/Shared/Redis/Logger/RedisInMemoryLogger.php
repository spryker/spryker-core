<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Redis\Logger;

use Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface;

class RedisInMemoryLogger implements RedisLoggerInterface
{
    /**
     * @var string[][]
     */
    protected static $calls = [];

    /**
     * @var \Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(RedisToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $dsn
     * @param string $command
     * @param array $payload
     * @param mixed|null $result
     *
     * @return void
     */
    public function logCall(string $dsn, string $command, array $payload, $result = null)
    {
        static::$calls[] = [
            'destination' => $dsn,
            'command' => $command,
            'payload' => $this->utilEncodingService->encodeJson($payload, JSON_PRETTY_PRINT) ?? '',
            'result' => $this->utilEncodingService->encodeJson($result, JSON_PRETTY_PRINT) ?? '',
        ];
    }

    /**
     * @return string[][]
     */
    public function getCalls(): array
    {
        return static::$calls;
    }
}
