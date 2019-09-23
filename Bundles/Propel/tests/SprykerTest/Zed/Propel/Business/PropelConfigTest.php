<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Business\Exception\UnSupportedDatabaseEngineException;
use Spryker\Zed\Propel\PropelConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelConfigTest
 * Add your own group annotations below this line
 */
class PropelConfigTest extends Unit
{
    /**
     * @return \Spryker\Zed\Propel\PropelConfig
     */
    private function getConfig()
    {
        return new PropelConfig();
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
    public function getPropelConfig()
    {
        $this->assertIsArray($this->getConfig()->getPropelConfig());
    }

    /**
     * @return void
     */
    public function testGetSchemaDirectoryShouldReturnPathToSchemas()
    {
        $this->assertTrue(is_dir($this->getConfig()->getSchemaDirectory()));
    }

    /**
     * @return void
     */
    public function testGetPropelSchemaPathPatterShouldReturnArrayWithPatternsToSchemaDirectories()
    {
        $pathPatterns = $this->getConfig()->getPropelSchemaPathPatterns();
        $this->assertTrue(is_array($pathPatterns));
    }

    /**
     * @return void
     */
    public function testGetLogPath()
    {
        $this->assertIsString($this->getConfig()->getLogPath());
    }

    /**
     * @return void
     */
    public function testGetCurrentDatabaseEngine()
    {
        $this->assertIsString($this->getConfig()->getCurrentDatabaseEngine());
    }

    /**
     * @return void
     */
    public function testGetCurrentDatabaseEngineName()
    {
        $this->assertIsString($this->getConfig()->getCurrentDatabaseEngineName());
    }

    /**
     * @return void
     */
    public function testGetCurrentDatabaseEngineNameThrowsException()
    {
        $propelConfigMock = $this->getPropelConfigMock();

        $this->expectException(UnSupportedDatabaseEngineException::class);
        $propelConfigMock->getCurrentDatabaseEngineName();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Propel\PropelConfig
     */
    protected function getPropelConfigMock()
    {
        $propelConfigMock = $this->getMockBuilder(PropelConfig::class)->setMethods(['getCurrentDatabaseEngine'])->getMock();
        $propelConfigMock->expects($this->once())->method('getCurrentDatabaseEngine')->willReturn('Un supported database engine');

        return $propelConfigMock;
    }
}
