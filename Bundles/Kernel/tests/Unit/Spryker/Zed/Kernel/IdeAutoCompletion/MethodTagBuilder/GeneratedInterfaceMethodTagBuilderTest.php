<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\GeneratedInterfaceMethodTagBuilder;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group IdeAutoCompletion
 * @group MethodTagBuilder
 * @group GeneratedInterfaceMethodTagBuilderTest
 */
class GeneratedInterfaceMethodTagBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testBuildMethodTagsShouldReturnArrayWithMethodsToGetGeneratedBundleInterface()
    {
        $expectedMethodTag = ' * @method \\Generated\Zed\Ide\Bundle bundle()';

        $this->assertContains(
            $expectedMethodTag,
            (new GeneratedInterfaceMethodTagBuilder())->buildMethodTags('Bundle')
        );
    }

}
