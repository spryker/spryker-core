<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Redis\WebProfiler;

use Spryker\Shared\Redis\Logger\RedisLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Throwable;

class RedisDataCollector extends DataCollector
{
    protected const COLLECTOR_NAME = 'redis';

    /**
     * @var \Spryker\Shared\Redis\Logger\RedisLoggerInterface
     */
    protected $redisLogger;

    /**
     * @param \Spryker\Shared\Redis\Logger\RedisLoggerInterface $redisLogger
     */
    public function __construct(RedisLoggerInterface $redisLogger)
    {
        $this->redisLogger = $redisLogger;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Throwable|null $exception
     *
     * @return void
     */
    public function collect(Request $request, Response $response, ?Throwable $exception = null)
    {
        $this->data['calls'] = $this->redisLogger->getCalls();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return static::COLLECTOR_NAME;
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->data = [];
    }

    /**
     * @return array
     */
    public function getCalls(): array
    {
        return $this->data['calls'];
    }
}
