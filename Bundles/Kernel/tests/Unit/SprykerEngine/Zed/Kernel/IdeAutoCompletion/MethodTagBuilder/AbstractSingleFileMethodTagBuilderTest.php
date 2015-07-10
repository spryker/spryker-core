<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion;

use Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\Fixtures\SingleFileMethodTagBuilder;

/**
 * @group Kernel
 * @group MethodTagBuilder
 */
class AbstractSingleFileMethodTagBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testBuildMethodTagsShouldReturnMethodTagWithVendorFileIfProjectDoesNotOverrideIt()
    {
        $methodTagBuilder = new SingleFileMethodTagBuilder([
            SingleFileMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'Communication/',
            SingleFileMethodTagBuilder::OPTION_KEY_FILE_NAME_SUFFIX => 'DependencyContainer.php',
            SingleFileMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src',
            SingleFileMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src',
        ]);

        $expectedMethodTag =
            ' * @method \VendorNamespace\Application\Bundle\Communication\BundleDependencyContainer singleFileMethod()'
        ;

        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    public function testBuildMethodTagsShouldReturnMethodTagWithProjectFileIfProjectOverrideIt()
    {
        $methodTagBuilder = new SingleFileMethodTagBuilder([
            SingleFileMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'Persistence/',
            SingleFileMethodTagBuilder::OPTION_KEY_FILE_NAME_SUFFIX => 'QueryContainer.php',
            SingleFileMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src',
            SingleFileMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src',
        ]);

        $expectedMethodTag =
            ' * @method \ProjectNamespace\Application\Bundle\Persistence\BundleQueryContainer singleFileMethod()'
        ;

        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    public function testBuildMethodTagsShouldReturnMethodTagWithProjectFileIfFileOnlyInProject()
    {
        $methodTagBuilder = new SingleFileMethodTagBuilder([
            SingleFileMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'Business/',
            SingleFileMethodTagBuilder::OPTION_KEY_FILE_NAME_SUFFIX => 'Facade.php',
            SingleFileMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src',
            SingleFileMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src',
        ]);

        $expectedMethodTag =
            ' * @method \ProjectNamespace\Application\Bundle\Business\BundleFacade singleFileMethod()'
        ;

        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    public function testBuildMethodTagsShouldReturnUnchangedArrayIfNoFileCanBeFound()
    {
        $methodTagBuilder = new SingleFileMethodTagBuilder([
            SingleFileMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'NotExisting/',
            SingleFileMethodTagBuilder::OPTION_KEY_FILE_NAME_SUFFIX => 'Facade.php',
            SingleFileMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src',
            SingleFileMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendor/*/*/src',
        ]);

        $givenMethodTags = [];
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle', $givenMethodTags);
        $this->assertSame($givenMethodTags, $methodTags);
    }

}
