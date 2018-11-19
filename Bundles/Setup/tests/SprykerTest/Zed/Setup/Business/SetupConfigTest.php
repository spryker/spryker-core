<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Setup\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Setup\SetupConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Setup
 * @group Business
 * @group SetupConfigTest
 * Add your own group annotations below this line
 */
class SetupConfigTest extends Unit
{
    /**
     * @return \Spryker\Zed\Setup\SetupConfig
     */
    private function getConfig()
    {
        return new SetupConfig();
    }

    /**
     * @return void
     */
    public function testGetPathForJobsPHPShouldReturnPathToJobs()
    {
        $this->assertTrue(is_file($this->getConfig()->getCronjobsDefinitionFilePath()));
    }

    /**
     * @return void
     */
    public function testGetJenkinsUrlShouldReturnString()
    {
        $this->assertTrue(is_string($this->getConfig()->getJenkinsUrl()));
    }

    /**
     * @return void
     */
    public function testGetJenkinsDirectoryShouldReturnPathToJenkinsDirectory()
    {
        $this->assertTrue(is_dir($this->getConfig()->getJenkinsDirectory()));
    }

    /**
     * @return void
     */
    public function testGetJenkinsJobDirectoryShouldReturnPathToJenkinsJobDirectory()
    {
        $this->assertTrue(is_dir($this->getConfig()->getJenkinsJobsDirectory()));
    }

    /**
     * @return void
     */
    public function testGetGeneratedDirectoryShouldReturnPathToGeneratedFiles()
    {
        $this->assertTrue(is_dir($this->getConfig()->getGeneratedDirectory()));
    }

    /**
     * @return void
     */
    public function testGetSetupInstallCommandNamesMustReturnArray()
    {
        $this->assertInternalType('array', $this->getConfig()->getSetupInstallCommandNames());
    }
}
