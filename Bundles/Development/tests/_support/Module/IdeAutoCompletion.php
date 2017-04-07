<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Development\Module;

use Codeception\Module;
use Codeception\TestCase;

class IdeAutoCompletion extends Module
{

    const TEST_TARGET_DIRECTORY = '/tmp/development-ide-auto-completion-test/';

    /**
     * @param \Codeception\TestCase $test
     *
     * @return void
     */
    public function _before(TestCase $test)
    {
        parent::_before($test);

        $this->removeTestTargetDirectory();
        $this->createTestTargetDirectory();
    }

    /**
     * @return void
     */
    protected function removeTestTargetDirectory()
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
    protected function createTestTargetDirectory()
    {
        mkdir(static::TEST_TARGET_DIRECTORY, 0777, true);
    }

    /**
     * @param \Codeception\TestCase $test
     *
     * @return void
     */
    public function _after(TestCase $test)
    {
        parent::_after($test);

        $this->removeTestTargetDirectory();
    }

    /**
     * @param \Codeception\TestCase $test
     * @param bool $fail
     *
     * @return void
     */
    public function _failed(TestCase $test, $fail)
    {
        parent::_failed($test, $fail);

        $this->removeTestTargetDirectory();
    }

}
