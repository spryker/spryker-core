<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion;

use SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\EntityMethodTagBuilder;

/**
 * @group Kernel
 * @group MethodTagBuilder
 * @group EntityMethodTagBuilder
 */
class EntityMethodTagBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testBuildMethodTagsShouldReturnVendorMethodTagIfProjectDoesNotOverrideIt()
    {
        $options = [
            EntityMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            EntityMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            EntityMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        $methodTagBuilder = new EntityMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag = ' * @method \VendorNamespace\Application\Bundle\Persistence\Propel\Foo entityFoo()';
        $this->assertContains($expectedMethodTag, $methodTags);

        $expectedMethodTag = ' * @method \VendorNamespace\Application\Bundle\Persistence\Propel\Bar entityBar()';
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    public function testBuildMethodTagsShouldReturnProjectMethodTagIfProjectOverrideIt()
    {
        $options = [
            EntityMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            EntityMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            EntityMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        $methodTagBuilder = new EntityMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag = ' * @method \ProjectNamespace\Application\Bundle\Persistence\Propel\Baz entityBaz()';
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    public function testBuildMethodTagsShouldNotReturnQueryClasses()
    {
        $options = [
            EntityMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            EntityMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            EntityMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        $methodTagBuilder = new EntityMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag =
            ' * @method \ProjectNamespace\Application\Bundle\Persistence\Propel\BazQuery entityBazQuery()'
        ;
        $this->assertNotContains($expectedMethodTag, $methodTags);
    }

    public function testBuildMethodTagsShouldNotReturnClassesFromDeeperLevel()
    {
        $options = [
            EntityMethodTagBuilder::OPTION_KEY_APPLICATION => 'Application',
            EntityMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src/',
            EntityMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src/',
        ];

        $methodTagBuilder = new EntityMethodTagBuilder($options);
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');

        $expectedMethodTag =
            ' * @method \ProjectNamespace\Application\Bundle\Persistence\Propel\Folder\Foo entityFolderFoo()'
        ;
        $this->assertNotContains($expectedMethodTag, $methodTags);
    }

}
