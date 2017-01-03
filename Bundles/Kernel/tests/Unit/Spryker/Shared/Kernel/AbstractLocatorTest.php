<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Kernel;

use PHPUnit_Framework_TestCase;
use Unit\Spryker\Shared\Kernel\Fixtures\MissingPropertyLocator;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Kernel
 * @group AbstractLocatorTest
 */
class AbstractLocatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCreateInstanceShouldThrowExceptionIfApplicationNotDefined()
    {
        $this->expectException('\Exception');

        new MissingPropertyLocator();
    }

    /**
     * @return void
     */
    public function testCanCreateShouldThrowException()
    {
        $this->expectException('\Exception');

        new MissingPropertyLocator();
    }

}
