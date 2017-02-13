<?php

namespace Spryker\Zed\Queue\Business\Worker\Plugin;

class IterationInterruptPlugin implements InterruptPluginInterface
{

    const DEFAULT_MAX_ITERATIONS = 1000;

    /**
     * @var int
     */
    protected $maxIterations;

    /**
     * @var int
     */
    protected $iterationCursor = 0;

    /**
     * @param int $maxIterations
     */
    public function __construct($maxIterations = self::DEFAULT_MAX_ITERATIONS)
    {
        $this->maxIterations = $maxIterations;
    }

    /**
     * @return void
     */
    public function tick()
    {
        $this->iterationCursor++;
    }

    /**
     * @return bool
     */
    public function isInterrupted()
    {
        return ($this->iterationCursor > $this->maxIterations);
    }

}
