<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Model;

use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingInterface;
use Spryker\Zed\Synchronization\SynchronizationConfig;

abstract class AbstractSynchronization
{

    const MESSAGE_TIMESTAMP = '_timestamp';

    /**
     * @var \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\Synchronization\SynchronizationConfig
     */
    protected $config;

    /**
     * AbstractSynchronizationManager constructor.
     *
     * @param \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\Synchronization\SynchronizationConfig $config
     */
    public function __construct(SynchronizationToUtilEncodingInterface $utilEncodingService, SynchronizationConfig $config)
    {
        $this->utilEncodingService = $utilEncodingService;
        $this->config = $config;
    }

    /**
     * @param string $queueName
     * @param string $key
     * @param array $value
     *
     * @return bool
     */
    protected function isInvalid($queueName, $key, array $value)
    {
        $numberOfWorker = $this->config->getQueueWorkerNumber($queueName);
        if ($numberOfWorker > 1 && $this->isOutdated($key, $value)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $key
     * @param array $value
     *
     * @return bool
     */
    protected function isOutdated($key, array $value)
    {
        $existEntry = $this->getExistEntryByKey($key);
        if ($existEntry !== null &&
            array_key_exists(static::MESSAGE_TIMESTAMP, $existEntry) &&
            $existEntry[static::MESSAGE_TIMESTAMP] > $value[static::MESSAGE_TIMESTAMP]
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function getEncodedValue($value)
    {
        return $this->utilEncodingService->encodeJson($value);
    }

    /**
     * @param string $key
     *
     * @return array
     */
    abstract protected function getExistEntryByKey($key);

}
