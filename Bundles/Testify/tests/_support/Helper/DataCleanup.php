<?php

namespace Testify\Helper;

use Codeception\TestCase;

class DataCleanup extends \Codeception\Module
{
    protected $cleanups = [];

    protected $config = ['cleanup' => true];

    public function _addCleanup(\Closure $closure)
    {
        $this->cleanups[] = $closure;
    }

    /**
     * Cleans up inserted data
     * @param TestCase $test
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