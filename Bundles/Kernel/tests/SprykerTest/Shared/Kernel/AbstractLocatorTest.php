<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel;

use Codeception\Test\Unit;
use SprykerTest\Shared\Kernel\Fixtures\MissingPropertyLocator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group AbstractLocatorTest
 * Add your own group annotations below this line
 */
class AbstractLocatorTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateInstanceShouldThrowExceptionIfApplicationNotDefined(): void
    {
        $this->expectException('\Exception');

        new MissingPropertyLocator();
    }

    /**
     * @return void
     */
    public function testCanCreateShouldThrowException(): void
    {
        $this->expectException('\Exception');

        new MissingPropertyLocator();
    }
}
