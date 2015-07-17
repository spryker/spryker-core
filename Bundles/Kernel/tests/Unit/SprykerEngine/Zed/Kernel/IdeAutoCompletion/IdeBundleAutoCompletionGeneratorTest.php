<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion;

use SprykerEngine\Zed\Kernel\IdeAutoCompletion\IdeBundleAutoCompletionGenerator;
use Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion\Fixtures\BundleNameFinder;
use Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion\Fixtures\MethodTagBuilder;

/**
 * @group Kernel
 * @group Generator
 * @group IdeAutoCompletion
 */
class IdeBundleAutoCompletionGeneratorTest extends AbstractAutoCompletion
{

    public function testFileShouldBeGenerated()
    {
        $this->cleanUpTestDir();

        $generator = new IdeBundleAutoCompletionGenerator($this->getOptions());
        $generator->create('');

        $this->assertFileExists($this->getFilePath());
    }

    public function testAddMethodTagBuilderShouldReturnGenerator()
    {
        $generator = new IdeBundleAutoCompletionGenerator($this->getOptions());
        $generator = $generator->addMethodTagBuilder(new MethodTagBuilder());

        $this->assertInstanceOf('SprykerEngine\Zed\Kernel\IdeAutoCompletion\IdeBundleAutoCompletionGenerator', $generator);
    }

    /**
     * @return string
     */
    private function getFilePath()
    {
        $interfaceName = $this->getOptions('')[IdeBundleAutoCompletionGenerator::OPTION_KEY_INTERFACE_NAME];

        return $this->baseDir . 'test/' . $interfaceName . '.php';
    }

    public function testFileShouldContainANamespace()
    {
        $generated = $this->getGeneratedFileContent();

        $namespace = $this->getOptions()[IdeBundleAutoCompletionGenerator::OPTION_KEY_NAMESPACE];
        $expectedNamespace = 'namespace ' . $namespace . ';';

        $this->assertContains($expectedNamespace, $generated);
    }

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
        $generator->create('');

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
