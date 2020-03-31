<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler\LifeTime;

class SessionRedisLifeTimeCalculator implements SessionRedisLifeTimeCalculatorInterface
{
    /**
     * @var int
     */
    protected $defaultSessionLifeTime;

    /**
     * @param int $defaultSessionLifeTime
     */
    public function __construct(int $defaultSessionLifeTime)
    {
        $this->defaultSessionLifeTime = $defaultSessionLifeTime;
    }

    /**
     * @return int
     */
    public function getZedSessionLifeTime(): int
    {
        return $this->defaultSessionLifeTime;
    }
}
