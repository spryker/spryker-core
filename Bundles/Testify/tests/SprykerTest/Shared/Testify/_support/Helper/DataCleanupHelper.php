<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Closure;
use Codeception\Module;
use Codeception\TestInterface;

class DataCleanupHelper extends Module
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
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
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
