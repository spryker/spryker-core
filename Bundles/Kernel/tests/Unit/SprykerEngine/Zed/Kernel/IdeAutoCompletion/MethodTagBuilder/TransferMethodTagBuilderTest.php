<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion;

use SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\TransferMethodTagBuilder;

/**
 * @group Kernel
 * @group MethodTagBuilder
 */
class TransferMethodTagBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testBuildMethodTagsShouldReturnVendorMethodTagIfProjectDoesNotOverrideIt()
    {
        $options = [
            TransferMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            TransferMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            TransferMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        $methodTagBuilder = new TransferMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag = ' * @method \VendorNamespace\Application\Bundle\Transfer\Foo transferFoo()';
        $this->assertContains($expectedMethodTag, $methodTags);

        $expectedMethodTag = ' * @method \VendorNamespace\Application\Bundle\Transfer\Bar transferBar()';
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    public function testBuildMethodTagsShouldReturnProjectMethodTagIfProjectOverrideIt()
    {
        $options = [
            TransferMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            TransferMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            TransferMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        $methodTagBuilder = new TransferMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag = ' * @method \ProjectNamespace\Application\Bundle\Transfer\Baz transferBaz()';
        $this->assertContains($expectedMethodTag, $methodTags);
    }

}
