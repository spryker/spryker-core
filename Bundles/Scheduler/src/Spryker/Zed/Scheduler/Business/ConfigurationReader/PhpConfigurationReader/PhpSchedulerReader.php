<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Scheduler\Business\ConfigurationReader\PhpConfigurationReader;

use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface;
use Spryker\Zed\Scheduler\SchedulerConfig;

class PhpSchedulerReader implements PhpSchedulerReaderInterface
{
    /**
     * @var \Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface
     */
    protected $store;

    /**
     * @var \Spryker\Zed\Scheduler\SchedulerConfig
     */
    protected $schedulerConfig;

    /**
     * @param \Spryker\Zed\Scheduler\Dependency\Store\SchedulerToStoreInterface $store
     * @param \Spryker\Zed\Scheduler\SchedulerConfig $schedulerConfig
     */
    public function __construct(SchedulerToStoreInterface $store, SchedulerConfig $schedulerConfig)
    {
        $this->store = $store;
        $this->schedulerConfig = $schedulerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    public function getPhpCronJobsConfiguration(SchedulerTransfer $schedulerTransfer): SchedulerTransfer
    {
        $jobs = [];

        include_once $this->schedulerConfig->getCronJobsDefinitionPhpFilePath();

        if (!empty($jobs)) {
            foreach ($jobs as $job) {
                $schedulerTransfer = $this->indexJobsByStores($schedulerTransfer, $job);
            }
        }

        return $schedulerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerTransfer $schedulerTransfer
     * @param array $job
     *
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    protected function indexJobsByStores(SchedulerTransfer $schedulerTransfer, array $job): SchedulerTransfer
    {
        foreach ($job['stores'] as $storeName) {
            if ($storeName !== $this->store->getStoreName()) {
                continue;
            }

            if (!empty($job['command'])) {
                $job['request'] = $this->getPrcRequestUrl($job);
            }

            $job['store'] = $storeName;

            $schedulerTransfer
                ->addJob($storeName . '__' . $job['name'], $job);
        }

        return $schedulerTransfer;
    }

    /**
     * @param array $job
     *
     * @return string
     */
    protected function getPrcRequestUrl(array $job): string
    {
        $requestParts = [
            'module' => '',
            'controller' => '',
            'action' => '',
        ];

        $command = explode(' ', $job['command']);

        foreach ($command as $part) {
            $segments = array_keys($requestParts);
            foreach ($segments as $segment) {
                if (strpos($part, $segment . '=') !== false) {
                    $requestParts[$segment] = str_replace('--' . $segment . '=', '', $part);
                }
            }
        }

        $requestKey = '/' . $requestParts['module'] . '/' . $requestParts['controller']
            . '/' . $requestParts['action'];

        return $requestKey;
    }
}
