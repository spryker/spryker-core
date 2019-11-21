<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Helper;

use Codeception\Module;
use Codeception\TestInterface;

class IdeAutoCompletion extends Module
{
    public const TEST_TARGET_DIRECTORY = '/tmp/development-ide-auto-completion-test/';

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $this->removeTestTargetDirectory();
        $this->createTestTargetDirectory();
    }

    /**
     * @return void
     */
    protected function removeTestTargetDirectory(): void
    {
        if (!is_dir(static::TEST_TARGET_DIRECTORY)) {
            return;
        }

        $this->getFilesystem()->deleteDir(static::TEST_TARGET_DIRECTORY);
    }

    /**
     * @return \Codeception\Module\Filesystem|\Codeception\Module
     */
    protected function getFilesystem()
    {
        return $this->getModule('Filesystem');
    }

    /**
     * @return void
     */
    protected function createTestTargetDirectory(): void
    {
        mkdir(static::TEST_TARGET_DIRECTORY, 0777, true);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        parent::_after($test);

        $this->removeTestTargetDirectory();
    }

    /**
     * @param \Codeception\TestInterface $test
     * @param \Exception $fail
     *
     * @return void
     */
    public function _failed(TestInterface $test, $fail): void
    {
        parent::_failed($test, $fail);

        $this->removeTestTargetDirectory();
    }
}
