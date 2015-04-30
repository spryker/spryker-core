<?php

namespace SprykerFeature\Zed\Payone\Business\Mode;


interface ModeDetectorInterface
{

    const MODE_TEST = 'test';
    const MODE_LIVE = 'live';

    /**
     * @return string
     */
    public function getMode();

}
