<?php

namespace Testify\Helper;

use Closure;
use Codeception\Module;
use Codeception\TestCase;

class DataCleanup extends Module
{

    /**
     * @var array
     */
    protected $cleanups = [];

    /**
     * @var array
     */
    protected $config = ['cleanup' => true];

    /**
     * @param \Closure $closure
     *
     * @return void
     */
    public function _addCleanup(Closure $closure)
    {
        $this->cleanups[] = $closure;
    }

    /**
     * Cleans up inserted data
     *
     * @param \Codeception\TestCase $test
     *
     * @return void
     */
    public function _after(TestCase $test)
    {
        if (!$this->config['cleanup']) {
            return;
        }
        foreach (array_reverse($this->cleanups) as $cleanup) {
            try {
                $cleanup();
            } catch (\Exception $e) {
                $this->debugSection('Cleanup Failure', $e->getMessage());
            }
        }
        $this->cleanups = [];
    }

}
