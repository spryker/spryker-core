<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Mode;

use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerFeature\Zed\Payone\PayoneConfig;

class ModeDetector implements ModeDetectorInterface
{

    /**
     * @var PayoneConfig
     */
    protected $config;

    /**
     * @param PayoneConfig $config
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
