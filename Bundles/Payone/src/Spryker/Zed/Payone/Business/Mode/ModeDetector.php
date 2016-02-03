<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Mode;

use Spryker\Shared\Payone\Dependency\ModeDetectorInterface;
use Spryker\Zed\Payone\PayoneConfig;

class ModeDetector implements ModeDetectorInterface
{

    /**
     * @var \Spryker\Zed\Payone\PayoneConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Payone\PayoneConfig $config
     */
    public function __construct(PayoneConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        $mode = $this->config->getMode();

        if ($mode === static::MODE_LIVE) {
            return static::MODE_LIVE;
        }

        return static::MODE_TEST;
    }

}
