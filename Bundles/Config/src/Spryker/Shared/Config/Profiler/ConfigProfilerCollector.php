<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Config\Profiler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Throwable;

class ConfigProfilerCollector implements DataCollectorInterface, ConfigProfilerCollectorInterface
{
    public const SPRYKER_CONFIG_PROFILER = 'spryker_config_profiler';

    /**
     * @var array|null
     */
    protected $profileData;

    /**
     * @param array $profileData
     */
    public function __construct(array $profileData)
    {
        $this->profileData = $profileData;
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
    }

    /**
     * @return string
     */
    public function getName()
    {
        return static::SPRYKER_CONFIG_PROFILER;
    }

    /**
     * @return array
     */
    public function getConfigs()
    {
        ksort($this->profileData);

        return $this->profileData;
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        $this->profileData = null;
    }
}
