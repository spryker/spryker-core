<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelOrm\Business\Builder;

use Propel\Runtime\Propel;
use Spryker\Zed\PropelOrm\Business\Builder\ObjectBuilder;
use SprykerTest\Zed\PropelOrm\Business\Builder\Fixtures\Foo;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PropelOrm
 * @group Business
 * @group Builder
 * @group ObjectBuilderTest
 * Add your own group annotations below this line
 */
class ObjectBuilderTest extends AbstractBuilderTester
{
    protected const FOO_BUILDER_CLASS = ObjectBuilder::class;

    /**
     * @return void
     */
    public function testSaveShouldNotThrowAnException(): void
    {
        // Arrange
        $connection = Propel::getConnection();
        $foo = new Foo();
        $foo->setIdFoo(1);

        // Act
        $foo->save($connection);
    }
}
