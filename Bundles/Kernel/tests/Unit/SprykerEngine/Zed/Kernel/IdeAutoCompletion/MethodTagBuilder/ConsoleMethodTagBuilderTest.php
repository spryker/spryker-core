<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\IdeAutoCompletion;

use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\ConsoleMethodTagBuilder;

/**
 * @group Kernel
 * @group MethodTagBuilder
 */
class ConsoleMethodTagBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testBuildMethodTagsShouldReturnVendorMethodTagIfProjectDoesNotOverrideIt()
    {
        $options = [
            ConsoleMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            ConsoleMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            ConsoleMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        $methodTagBuilder = new ConsoleMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');
        $expectedMethodTag = ' * @method \VendorNamespace\Application\Bundle\Communication\Console\Foo consoleFoo()';
        $this->assertContains($expectedMethodTag, $methodTags);

        $expectedMethodTag = ' * @method \VendorNamespace\Application\Bundle\Communication\Console\Bar consoleBar()';
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    /**
     * @return void
     */
    public function testBuildMethodTagsShouldReturnProjectMethodTagIfProjectOverrideIt()
    {
        $options = [
            ConsoleMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            ConsoleMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            ConsoleMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        $methodTagBuilder = new ConsoleMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag = ' * @method \ProjectNamespace\Application\Bundle\Communication\Console\Baz consoleBaz()';
        $this->assertContains($expectedMethodTag, $methodTags);
    }

}
