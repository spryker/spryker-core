<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

use Unit\Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\Fixtures\SingleFileMethodTagBuilder;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group IdeAutoCompletion
 * @group MethodTagBuilder
 * @group AbstractSingleFileMethodTagBuilderTest
 */
class AbstractSingleFileMethodTagBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testBuildMethodTagsShouldReturnMethodTagWithVendorFileIfProjectDoesNotOverrideIt()
    {
        $methodTagBuilder = new SingleFileMethodTagBuilder([
            SingleFileMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'Layer/',
            SingleFileMethodTagBuilder::OPTION_KEY_FILE_NAME_SUFFIX => 'CoreOnly.php',
            SingleFileMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src',
            SingleFileMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendorMock/*/*/src',
        ]);

        $expectedMethodTag =
            ' * @method \VendorNamespace\Application\Bundle\Layer\BundleCoreOnly singleFileMethod()';

        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    /**
     * @return void
     */
    public function testBuildMethodTagsShouldReturnMethodTagWithProjectFileIfProjectOverrideIt()
    {
        $methodTagBuilder = new SingleFileMethodTagBuilder([
            SingleFileMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'Layer/',
            SingleFileMethodTagBuilder::OPTION_KEY_FILE_NAME_SUFFIX => 'CoreAndProject.php',
            SingleFileMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src',
            SingleFileMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendorMock/*/*/src',
        ]);

        $expectedMethodTag =
            ' * @method \ProjectNamespace\Application\Bundle\Layer\BundleCoreAndProject singleFileMethod()';

        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    /**
     * @return void
     */
    public function testBuildMethodTagsShouldReturnMethodTagWithProjectFileIfFileOnlyInProject()
    {
        $methodTagBuilder = new SingleFileMethodTagBuilder([
            SingleFileMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'Layer/',
            SingleFileMethodTagBuilder::OPTION_KEY_FILE_NAME_SUFFIX => 'ProjectOnly.php',
            SingleFileMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src',
            SingleFileMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendorMock/*/*/src',
        ]);

        $expectedMethodTag =
            ' * @method \ProjectNamespace\Application\Bundle\Layer\BundleProjectOnly singleFileMethod()';

        $methodTags = $methodTagBuilder->buildMethodTags('Bundle');
        $this->assertContains($expectedMethodTag, $methodTags);
    }

    /**
     * @return void
     */
    public function testBuildMethodTagsShouldReturnUnchangedArrayIfNoFileCanBeFound()
    {
        $methodTagBuilder = new SingleFileMethodTagBuilder([
            SingleFileMethodTagBuilder::OPTION_KEY_PATH_PATTERN => 'NotExisting/',
            SingleFileMethodTagBuilder::OPTION_KEY_FILE_NAME_SUFFIX => 'Facade.php',
            SingleFileMethodTagBuilder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/src',
            SingleFileMethodTagBuilder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/vendorMock/*/*/src',
        ]);

        $givenMethodTags = [];
        $methodTags = $methodTagBuilder->buildMethodTags('Bundle', $givenMethodTags);
        $this->assertSame($givenMethodTags, $methodTags);
    }

}
