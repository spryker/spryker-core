<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\IdeAutoCompletion;

use Spryker\Zed\Kernel\IdeAutoCompletion\IdeBundleAutoCompletionGenerator;
use Unit\Spryker\Zed\Kernel\IdeAutoCompletion\Fixtures\BundleNameFinder;
use Unit\Spryker\Zed\Kernel\IdeAutoCompletion\Fixtures\MethodTagBuilder;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group IdeAutoCompletion
 * @group IdeBundleAutoCompletionGeneratorTest
 */
class IdeBundleAutoCompletionGeneratorTest extends AbstractAutoCompletion
{

    /**
     * @return void
     */
    public function testFileShouldBeGenerated()
    {
        $this->cleanUpTestDir();

        $generator = new IdeBundleAutoCompletionGenerator($this->getOptions());
        $generator->create();

        $this->assertFileExists($this->getFilePath());
    }

    /**
     * @return void
     */
    public function testAddMethodTagBuilderShouldReturnGenerator()
    {
        $generator = new IdeBundleAutoCompletionGenerator($this->getOptions());
        $generator = $generator->addMethodTagBuilder(new MethodTagBuilder());

        $this->assertInstanceOf('Spryker\Zed\Kernel\IdeAutoCompletion\IdeBundleAutoCompletionGenerator', $generator);
    }

    /**
     * @return string
     */
    private function getFilePath()
    {
        $interfaceName = $this->getOptions('')[IdeBundleAutoCompletionGenerator::OPTION_KEY_INTERFACE_NAME];

        return $this->baseDir . 'test/' . $interfaceName . '.php';
    }

    /**
     * @return void
     */
    public function testFileShouldContainANamespace()
    {
        $generated = $this->getGeneratedFileContent();

        $namespace = $this->getOptions()[IdeBundleAutoCompletionGenerator::OPTION_KEY_NAMESPACE];
        $expectedNamespace = 'namespace ' . $namespace . ';';

        $this->assertContains($expectedNamespace, $generated);
    }

    /**
     * @return void
     */
    public function testFileShouldContainInterfaceForEachFoundBundle()
    {
        $generatedFile = $this->getGeneratedFileContent();

        $bundles = ['BundleA', 'BundleB', 'BundleC', 'BundleD'];
        foreach ($bundles as $bundle) {
            $this->assertContains('interface ' . $bundle, $generatedFile);
        }
    }

    /**
     * @return string
     */
    private function getGeneratedFileContent()
    {
        $generator = new IdeBundleAutoCompletionGenerator($this->getOptions());
        $generator->addMethodTagBuilder(new MethodTagBuilder());
        $generator->create();

        return file_get_contents($this->getFilePath());
    }

    /**
     * @return array
     */
    private function getOptions()
    {
        $options = [
            IdeBundleAutoCompletionGenerator::OPTION_KEY_BUNDLE_NAME_FINDER => new BundleNameFinder(),
            IdeBundleAutoCompletionGenerator::OPTION_KEY_NAMESPACE => 'Generated\Zed\Ide\AutoCompletion',
            IdeBundleAutoCompletionGenerator::OPTION_KEY_INTERFACE_NAME => 'TestInterface',
            IdeBundleAutoCompletionGenerator::OPTION_KEY_LOCATION_DIR => $this->baseDir . 'test/',
        ];

        return $options;
    }

}
