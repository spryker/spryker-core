<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Payone\Dependency;

interface ModeDetectorInterface
{

    const MODE_TEST = 'test';
    const MODE_LIVE = 'live';

    /**
     * @return string
     */
    public function getMode();

}
