<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ZedRequest\WebProfiler;

use Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Throwable;

class ZedRequestDataCollector extends DataCollector
{
    protected const COLLECTOR_NAME = 'zed_request';

    /**
     * @var \Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface
     */
    protected $zedRequestLogger;

    /**
     * @param \Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface $zedRequestLogger
     */
    public function __construct(ZedRequestLoggerInterface $zedRequestLogger)
    {
        $this->zedRequestLogger = $zedRequestLogger;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Throwable|null $exception
     *
     * @return void
     */
    public function collect(Request $request, Response $response, ?Throwable $exception = null): void
    {
        $this->data['logs'] = $this->zedRequestLogger->getLogs();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::COLLECTOR_NAME;
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        $this->data = [];
    }

    /**
     * @return array
     */
    public function getLogs(): array
    {
        return $this->data['logs'];
    }
}
