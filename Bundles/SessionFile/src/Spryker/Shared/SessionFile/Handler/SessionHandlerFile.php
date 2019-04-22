<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile\Handler;

use Spryker\Shared\SessionFile\Dependency\Service\SessionFileToMonitoringServiceInterface;

class SessionHandlerFile implements SessionHandlerInterface
{
    public const METRIC_SESSION_DELETE_TIME = 'File/Session_delete_time';
    public const METRIC_SESSION_WRITE_TIME = 'File/Session_write_time';
    public const METRIC_SESSION_READ_TIME = 'File/Session_read_time';

    /**
     * @var string
     */
    protected $keyPrefix = 'session:';

    /**
     * @var int
     */
    protected $lifetime;

    /**
     * @var string
     */
    protected $savePath;

    /**
     * @var \Spryker\Shared\SessionFile\Dependency\Service\SessionFileToMonitoringServiceInterface
     */
    protected $monitoringService;

    /**
     * @param string $savePath
     * @param int $lifetime
     * @param \Spryker\Shared\SessionFile\Dependency\Service\SessionFileToMonitoringServiceInterface $monitoringService
     */
    public function __construct(string $savePath, int $lifetime, SessionFileToMonitoringServiceInterface $monitoringService)
    {
        $this->savePath = $savePath;
        $this->lifetime = $lifetime;
        $this->monitoringService = $monitoringService;
    }

    /**
     * @param string $savePath
     * @param string $sessionName
     *
     * @return bool
     */
    public function open(string $savePath, string $sessionName): bool
    {
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0775, true);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function read(string $sessionId): string
    {
        $startTime = microtime(true);
        $key = $this->keyPrefix . $sessionId;
        $sessionFile = $this->savePath . DIRECTORY_SEPARATOR . $key;
        if (!file_exists($sessionFile)) {
            return '';
        }

        $content = file_get_contents($sessionFile);

        $this->monitoringService->addCustomParameter(self::METRIC_SESSION_READ_TIME, microtime(true) - $startTime);

        return ($content === false) ? '' : $content;
    }

    /**
     * @param string $sessionId
     * @param string $sessionData
     *
     * @return bool
     */
    public function write(string $sessionId, string $sessionData): bool
    {
        $key = $this->keyPrefix . $sessionId;

        if (strlen($sessionData) < 1) {
            return false;
        }

        $startTime = microtime(true);
        $result = file_put_contents($this->savePath . DIRECTORY_SEPARATOR . $key, $sessionData);
        $this->monitoringService->addCustomParameter(self::METRIC_SESSION_WRITE_TIME, microtime(true) - $startTime);

        return $result > 0;
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy(string $sessionId): bool
    {
        $key = $this->keyPrefix . $sessionId;
        $file = $this->savePath . DIRECTORY_SEPARATOR . $key;
        if (file_exists($file)) {
            $startTime = microtime(true);
            unlink($file);
            $this->monitoringService->addCustomParameter(self::METRIC_SESSION_DELETE_TIME, microtime(true) - $startTime);
        }

        return true;
    }

    /**
     * @param int $maxLifetime
     *
     * @return bool
     */
    public function gc(int $maxLifetime): bool
    {
        foreach (glob($this->savePath . DIRECTORY_SEPARATOR . $this->keyPrefix . '*') as $file) {
            if (filemtime($file) + $maxLifetime < time() && file_exists($file)) {
                unlink($file);
            }
        }

        return true;
    }
}
