<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Model\Validation;

use Spryker\Zed\Synchronization\SynchronizationConfig;

class OutdatedValidator implements OutdatedValidatorInterface
{
    const MESSAGE_TIMESTAMP = '_timestamp';

    /**
     * @var \Spryker\Zed\Synchronization\SynchronizationConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Synchronization\SynchronizationConfig $config
     */
    public function __construct(SynchronizationConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $queueName
     * @param array $newEntry
     * @param array $existingEntry
     *
     * @return bool
     */
    public function isInvalid($queueName, array $newEntry, array $existingEntry)
    {
        $numberOfWorker = $this->config->getQueueWorkerNumber($queueName);
        if ($numberOfWorker > 1 && $this->isOutdated($newEntry, $existingEntry)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $newEntry
     * @param array $existingEntry
     *
     * @return bool
     */
    protected function isOutdated(array $newEntry, array $existingEntry)
    {
        if (!empty($existingEntry) &&
            array_key_exists(static::MESSAGE_TIMESTAMP, $existingEntry) &&
            $existingEntry[static::MESSAGE_TIMESTAMP] > $newEntry[static::MESSAGE_TIMESTAMP]
        ) {
            return true;
        }

        return false;
    }
}
