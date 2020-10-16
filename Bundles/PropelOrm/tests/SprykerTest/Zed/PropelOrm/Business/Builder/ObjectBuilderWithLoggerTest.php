<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelOrm\Business\Builder;

use Spryker\Zed\PropelOrm\Business\Builder\ObjectBuilderWithLogger;
use SprykerTest\Zed\PropelOrm\Business\Builder\Fixtures\Foo;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PropelOrm
 * @group Business
 * @group Builder
 * @group ObjectBuilderWithLoggerTest
 * Add your own group annotations below this line
 */
class ObjectBuilderWithLoggerTest extends AbstractBuilderTester
{
    protected const FOO_BUILDER_CLASS = ObjectBuilderWithLogger::class;

    /**
     * @return void
     */
    public function testSaveShouldNotThrowAnException(): void
    {
        // Arrange
        $foo = new Foo();
        $foo->setIdFoo(1);

        // Act
        $foo->save();
    }
}
