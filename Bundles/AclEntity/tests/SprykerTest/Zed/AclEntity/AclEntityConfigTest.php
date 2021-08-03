<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AclEntity;

use Codeception\Test\Unit;
use Spryker\Zed\AclEntity\AclEntityConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AclEntity
 * @group AclEntityConfigTest
 * Add your own group annotations below this line
 */
class AclEntityConfigTest extends Unit
{
    protected const EXPECTED_GLOBAL_OPERATION_MASK = 0;

    /**
     * @return void
     */
    public function testGetDefaultGlobalOperationMask(): void
    {
        // Arrange
        $config = new AclEntityConfig();

        // Act
        $defaultGlobalOperationMask = $config->getDefaultGlobalOperationMask();

        // Assert
        $this->assertSame(static::EXPECTED_GLOBAL_OPERATION_MASK, $defaultGlobalOperationMask);
    }
}
