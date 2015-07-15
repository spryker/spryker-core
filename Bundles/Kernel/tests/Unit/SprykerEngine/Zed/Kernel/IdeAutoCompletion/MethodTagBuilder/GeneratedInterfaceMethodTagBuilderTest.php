<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion;

use SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\GeneratedInterfaceMethodTagBuilder;

/**
 * @group Kernel
 * @group MethodTagBuilder
 */
class GeneratedInterfaceMethodTagBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testBuildMethodTagsShouldReturnArrayWithMethodsToGetGeneratedBundleInterface()
    {
        $expectedMethodTag = ' * @method \\Generated\Zed\Ide\Bundle bundle()';

        $this->assertContains(
            $expectedMethodTag,
            (new GeneratedInterfaceMethodTagBuilder())->buildMethodTags('Bundle')
        );
    }

}
