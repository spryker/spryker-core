<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Development\Business\IdeAutoCompletion\Generator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\IdeAutoCompletionBundleMethodTransfer;
use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\BundleGenerator;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Development
 * @group Business
 * @group IdeAutoCompletion
 * @group Generator
 * @group BundleGeneratorTest
 */
class BundleGeneratorTest extends Unit
{

    /**
     * @dataProvider provideExpectedResultWithMethods()
     *
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[] $bundleTransferCollection
     * @param string $expectedFileContent
     *
     * @return void
     */
    public function testGeneratorAddsBundleInterfaceThatHasMethods($bundleTransferCollection, $expectedFileContent)
    {
        $generator = new BundleGenerator($this->createTwigEnvironment(), $this->getGeneratorOptions());
        $fileContent = $generator->generate($bundleTransferCollection);

        $this->assertSame($expectedFileContent, $fileContent);
    }

    /**
     * @return array
     */
    public function provideExpectedResultWithMethods()
    {
        $fileContent = <<<'EOD'
<?php

namespace Generated\FooApplication\Ide;

/**
 * @method \Generated\FooApplication\Ide\FooBundle fooBundle()
 */
interface AutoCompletion
{}

EOD;

        return [
            [
                $this->createBundleTransferCollectionWithMethods(),
                $fileContent,
            ],
        ];
    }

    /**
     * @dataProvider provideExpectedResultWithoutMethods()
     *
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[] $bundleTransferCollection
     * @param string $expectedFileContent
     *
     * @return void
     */
    public function testGeneratorOmitsBundleInterfaceThatHasNoMethods($bundleTransferCollection, $expectedFileContent)
    {
        $generator = new BundleGenerator($this->createTwigEnvironment(), $this->getGeneratorOptions());
        $fileContent = $generator->generate($bundleTransferCollection);

        $this->assertSame($expectedFileContent, $fileContent);
    }

    /**
     * @return array
     */
    public function provideExpectedResultWithoutMethods()
    {
        $fileContent = <<<'EOD'
<?php

namespace Generated\FooApplication\Ide;

/**
 */
interface AutoCompletion
{}

EOD;

        return [
            [
                $this->createBundleTransferCollectionWithoutMethods(),
                $fileContent,
            ],
        ];
    }

    /**
     * @return \Twig_Environment
     */
    protected function createTwigEnvironment()
    {
        $twigLoader = new Twig_Loader_Filesystem([
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/Development/src/Spryker/Zed/Development/Business/IdeAutoCompletion/Generator/Templates/',
        ]);

        return new Twig_Environment($twigLoader);
    }

    /**
     * @return array
     */
    protected function getGeneratorOptions()
    {
        return [
            IdeAutoCompletionOptionConstants::APPLICATION_NAME => 'FooApplication',
            IdeAutoCompletionOptionConstants::TARGET_NAMESPACE_PATTERN => sprintf(
                'Generated\%s\Ide',
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER
            ),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[]
     */
    protected function createBundleTransferCollectionWithoutMethods()
    {
        return [$this->createBundleTransfer()];
    }

    /**
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[]
     */
    protected function createBundleTransferCollectionWithMethods()
    {
        $bundleTransfer = $this->createBundleTransfer();
        $bundleTransfer->addMethod($this->createBundleMethodTransfer());

        return [$bundleTransfer];
    }

    /**
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer
     */
    protected function createBundleTransfer()
    {
        $bundleTransfer = new IdeAutoCompletionBundleTransfer();
        $bundleTransfer->setName('FooBundle');
        $bundleTransfer->setNamespaceName('Generated\FooApplication\Ide');
        $bundleTransfer->setMethodName('fooBundle');
        $bundleTransfer->setDirectory('/foo/bar/baz/FooBundle/src/Spryker/FooApplication/Business/');
        $bundleTransfer->setBaseDirectory('/foo/bar/baz/FooBundle/src/');

        return $bundleTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleMethodTransfer
     */
    protected function createBundleMethodTransfer()
    {
        $bundleMethodTransfer = new IdeAutoCompletionBundleMethodTransfer();
        $bundleMethodTransfer->setName('');
        $bundleMethodTransfer->setNamespaceName('');
        $bundleMethodTransfer->setClassName('');

        return $bundleMethodTransfer;
    }

}
