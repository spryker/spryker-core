<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion;

use SprykerEngine\Zed\Kernel\IdeAutoCompletion\IdeAutoCompletionGenerator;
use Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion\Fixtures\BundleNameFinder;
use Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion\Fixtures\MethodTagBuilder;

/**
 * @group Kernel
 * @group Generator
 * @group IdeAutoCompletion
 */
class IdeAutoCompletionGeneratorTest extends AbstractAutoCompletion
{

    /**
     * @var string
     */
    private $generatedFileContent;

    /**
     * @var string
     */
    private $pathToFile;

    public function getGeneratedFileContent()
    {
        if (is_null($this->generatedFileContent)) {
            $options = $this->getOptions();

            $generator = new IdeAutoCompletionGenerator($options);

            $generator->create('');

            $interfaceName = $this->getOptions('')[IdeAutoCompletionGenerator::OPTION_KEY_INTERFACE_NAME];
            $this->pathToFile = $this->baseDir . 'test/' . $interfaceName . '.php';
            $this->generatedFileContent = file_get_contents($this->pathToFile);
        }

        return $this->generatedFileContent;
    }

    public function testDirectoryShouldBeCreatedIfNotExists()
    {
        $this->generatedFileContent = null;

        $dirToTest = $this->baseDir . 'test/';
        $this->cleanUpTestDir();

        $this->assertFalse(is_dir($dirToTest));

        $this->getGeneratedFileContent();

        $this->assertTrue(is_dir($dirToTest));
        $this->generatedFileContent = null;
    }

    public function testFileShouldBeGenerated()
    {
        $this->getGeneratedFileContent();

        $this->assertFileExists($this->pathToFile);
    }

    public function testFileShouldContainNamespace()
    {
        $generatedFile = $this->getGeneratedFileContent();
        $namespace = $this->getOptions('')[IdeAutoCompletionGenerator::OPTION_KEY_NAMESPACE];

        $this->assertContains('namespace ' . $namespace . ';', $generatedFile);
    }

    public function testAddMethodTagBuilderShouldReturnGenerator()
    {
        $generator = new IdeAutoCompletionGenerator($this->getOptions());
        $generator = $generator->addMethodTagBuilder(new MethodTagBuilder());

        $this->assertInstanceOf('SprykerEngine\Zed\Kernel\IdeAutoCompletion\IdeAutoCompletionGenerator', $generator);
    }

    public function testGetNameShouldReturnNameOfGenerator()
    {
        $options = $this->getOptions();
        $this->assertSame(
            IdeAutoCompletionGenerator::GENERATOR_NAME,
            (new IdeAutoCompletionGenerator($this->getOptions()))->getName()
        );
    }

    /**
     * @return array
     */
    private function getOptions()
    {
        $options = [
            IdeAutoCompletionGenerator::OPTION_KEY_BUNDLE_NAME_FINDER => new BundleNameFinder(),
            IdeAutoCompletionGenerator::OPTION_KEY_NAMESPACE => 'Generated\Zed\Ide',
            IdeAutoCompletionGenerator::OPTION_KEY_INTERFACE_NAME => 'TestInterface',
            IdeAutoCompletionGenerator::OPTION_KEY_LOCATION_DIR => $this->baseDir . '/test/',
        ];

        return $options;
    }

}
