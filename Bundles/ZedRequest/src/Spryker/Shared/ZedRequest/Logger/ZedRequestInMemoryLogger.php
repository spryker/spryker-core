<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Logger;

use Spryker\Shared\ZedRequest\Dependency\Service\ZedRequestToUtilEncodingServiceInterface;

class ZedRequestInMemoryLogger implements ZedRequestLoggerInterface
{
    /**
     * @var array
     */
    protected static $logs = [];

    /**
     * @var \Spryker\Shared\ZedRequest\Dependency\Service\ZedRequestToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Shared\ZedRequest\Dependency\Service\ZedRequestToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ZedRequestToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $url
     * @param array $payload
     * @param array $result
     *
     * @return void
     */
    public function log(string $url, array $payload, array $result): void
    {
        static::$logs[] = [
            'destination' => $url,
            'payload' => $this->utilEncodingService->encodeJson($payload, JSON_PRETTY_PRINT) ?? '',
            'result' => $this->utilEncodingService->encodeJson($result, JSON_PRETTY_PRINT) ?? '',
        ];
    }

    /**
     * @return array
     */
    public function getLogs(): array
    {
        return static::$logs;
    }
}
