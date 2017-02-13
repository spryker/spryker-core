<?php

namespace Spryker\Zed\Queue\Business\Worker\Plugin;

class TimeoutInterruptPlugin implements InterruptPluginInterface
{

    const DEFAULT_TTL_SECONDS = 3600;

    /**
     * @var int
     */
    protected $ttlSeconds;

    /**
     * @var int
     */
    protected $startTime;

    /**
     * @param int $ttlSeconds
     */
    public function __construct($ttlSeconds = self::DEFAULT_TTL_SECONDS)
    {
        $this->ttlSeconds = $ttlSeconds;
        $this->startTime = time();
    }

    /**
     * @return void
     */
    public function tick()
    {
    }

    /**
     * @return bool
     */
    public function isInterrupted()
    {
        $elapsedTime = (time() - $this->startTime);

        return ($elapsedTime > $this->ttlSeconds);
    }

}
