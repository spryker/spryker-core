<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel;

use PHPUnit_Framework_TestCase;
use SprykerTest\Shared\Kernel\Fixtures\MissingPropertyLocator;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group AbstractLocatorTest
 * Add your own group annotations below this line
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
