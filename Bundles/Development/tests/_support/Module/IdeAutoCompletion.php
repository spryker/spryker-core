<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Development\Module;

use Codeception\Module;
use Codeception\Scenario;
use Codeception\Step\Action;
use Codeception\TestCase;
use Codeception\TestInterface;

class IdeAutoCompletion extends Module
{

    const TEST_TARGET_DIRECTORY = '/tmp/development-ide-auto-completion-test/';

    /**
     * @param \Codeception\TestCase $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);
        
        $this->removeTestTargetDirectory($test->getMetadata()->getFeature());
        $this->createTestTargetDirectory();
    }

    /**
     * @param \Codeception\Scenario $scenario
     *
     * @return void
     */
    protected function removeTestTargetDirectory(Scenario $scenario)
    {
        if (!is_dir(static::TEST_TARGET_DIRECTORY)) {
            return;
        }

        $scenario->runStep(new Action('deleteDir', [static::TEST_TARGET_DIRECTORY]));
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

        $this->removeTestTargetDirectory($test->getScenario());
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

        $this->removeTestTargetDirectory($test->getScenario());
    }

}
