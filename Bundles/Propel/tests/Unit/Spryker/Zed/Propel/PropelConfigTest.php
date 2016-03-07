<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Propel;

use Spryker\Zed\Propel\PropelConfig;

class PropelConfigTest extends \PHPUnit_Framework_TestCase
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

}
