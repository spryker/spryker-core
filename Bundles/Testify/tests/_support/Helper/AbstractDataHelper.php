<?php

namespace Testify\Helper;

use Codeception\TestCase;

abstract class AbstractDataHelper extends \Codeception\Module
{
    protected $cleanups = [];

    protected $config = ['cleanup' => false];

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
            $cleanup();
        }
        $this->cleanups = [];
    }

}