<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Closure;
use Codeception\Module;
use Codeception\TestInterface;
use Exception;

class DataCleanupHelper extends Module
{
    use ModuleHelperConfigTrait;

    /**
     * @var array
     */
    protected $cleanups = [];

    /**
     * @param \Closure $closure
     *
     * @return void
     */
    public function _addCleanup(Closure $closure): void
    {
        $this->cleanups[] = $closure;
    }

    /**
     * @param \Closure $closure
     *
     * @return void
     */
    public function addCleanup(Closure $closure): void
    {
        $this->_addCleanup($closure);
    }

    /**
     * Cleans up inserted data
     *
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        if (!$this->config['cleanup']) {
            return;
        }
        foreach (array_reverse($this->cleanups) as $cleanup) {
            try {
                $cleanup();
            } catch (Exception $e) {
                $this->debugSection('Cleanup Failure', $e->getMessage());
            }
        }
        $this->cleanups = [];
    }

    /**
     * @return void
     */
    protected function setDefaultConfig(): void
    {
        $this->config = ['cleanup' => true];
    }
}
