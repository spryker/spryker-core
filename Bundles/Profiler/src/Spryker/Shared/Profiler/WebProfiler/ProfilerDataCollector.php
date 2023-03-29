<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Profiler\WebProfiler;

use Generated\Shared\Transfer\ProfilerDataTransfer;
use Spryker\Shared\Profiler\ProfilerData\ProfilerDataStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Throwable;

class ProfilerDataCollector extends DataCollector
{
    /**
     * @var string
     */
    protected const COLLECTOR_NAME = 'profiler';

    /**
     * @var \Spryker\Shared\Profiler\ProfilerData\ProfilerDataStorageInterface
     */
    protected ProfilerDataStorageInterface $profilerDataStorage;

    /**
     * @param \Spryker\Shared\Profiler\ProfilerData\ProfilerDataStorageInterface $profilerDataStorage
     */
    public function __construct(ProfilerDataStorageInterface $profilerDataStorage)
    {
        $this->profilerDataStorage = $profilerDataStorage;
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
        $this->data['profilerData'] = $this->profilerDataStorage->getProfilerData();
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
     * used in twig template
     *
     * @return \Generated\Shared\Transfer\ProfilerDataTransfer
     */
    public function getProfilerData(): ProfilerDataTransfer
    {
        return $this->data['profilerData'] ?? new ProfilerDataTransfer();
    }
}
