<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Redis\Logger;

use Generated\Shared\Transfer\RedisConfigurationTransfer;
use Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface;

class RedisInMemoryLogger implements RedisLoggerInterface
{
    protected const DSN_STRING_TEMPLATE_UNKNOWN = 'unknown';

    /**
     * @var string[][]
     */
    protected static $logs = [];

    /**
     * @var \Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var string
     */
    protected $dsnString;

    /**
     * @param \Spryker\Shared\Redis\Dependency\Service\RedisToUtilEncodingServiceInterface $utilEncodingService
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer|null $redisConfigurationTransfer
     */
    public function __construct(RedisToUtilEncodingServiceInterface $utilEncodingService, ?RedisConfigurationTransfer $redisConfigurationTransfer = null)
    {
        $this->utilEncodingService = $utilEncodingService;
        $this->buildDsnString($redisConfigurationTransfer);
    }

    /**
     * @param string $command
     * @param array $payload
     * @param mixed|null $result
     *
     * @return void
     */
    public function log(string $command, array $payload, $result = null)
    {
        $payload = $this->normalizePayload($payload);

        if (isset($payload['value'])) {
            $payload['value'] = $this->isBinaryString($payload['value']) ? bin2hex($payload['value']) : $payload['value'];
        }

        static::$logs[] = [
            'destination' => $this->dsnString,
            'command' => $command,
            'payload' => stripslashes($this->utilEncodingService->encodeJson($payload, JSON_PRETTY_PRINT) ?? ''),
            'result' => stripslashes($this->utilEncodingService->encodeJson($result, JSON_PRETTY_PRINT) ?? ''),
        ];
    }

    /**
     * @return string[][]
     */
    public function getLogs(): array
    {
        return static::$logs;
    }

    /**
     * @param array $payload
     *
     * @return array
     */
    protected function normalizePayload(array $payload): array
    {
        $normalizedPayload = [];

        foreach ($payload as $key => $value) {
            if (!is_string($value) || !$this->isBinaryString($value)) {
                $normalizedPayload[$key] = $value;

                continue;
            }

            $normalizedPayload[$key] = sprintf('[binary]%s', bin2hex($value));
        }

        return $normalizedPayload;
    }

    /**
     * @param string $data
     *
     * @return bool
     */
    protected function isBinaryString(string $data): bool
    {
        return preg_match('~[^\x20-\x7E\t\r\n]~', $data) > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\RedisConfigurationTransfer|null $redisConfigurationTransfer
     *
     * @return void
     */
    protected function buildDsnString(?RedisConfigurationTransfer $redisConfigurationTransfer = null): void
    {
        $dsnString = static::DSN_STRING_TEMPLATE_UNKNOWN;

        if (!$redisConfigurationTransfer) {
            $this->dsnString = $dsnString;

            return;
        }

        $dataSourceNames = $redisConfigurationTransfer->getDataSourceNames();

        if ($dataSourceNames) {
            $this->dsnString = implode(',', $dataSourceNames);

            return;
        }

        $connectionCredentialsTransfer = $redisConfigurationTransfer->getConnectionCredentials();

        if ($connectionCredentialsTransfer) {
            $dsnString = sprintf(
                '%s://%s:%s/%s',
                $connectionCredentialsTransfer->getScheme() ?? static::DSN_STRING_TEMPLATE_UNKNOWN,
                $connectionCredentialsTransfer->getHost() ?? static::DSN_STRING_TEMPLATE_UNKNOWN,
                $connectionCredentialsTransfer->getPort() ?? static::DSN_STRING_TEMPLATE_UNKNOWN,
                $connectionCredentialsTransfer->getDatabase() ?? static::DSN_STRING_TEMPLATE_UNKNOWN
            );
        }

        $this->dsnString = $dsnString;
    }
}
