<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\IdeAutoCompletion;

use Spryker\Zed\Kernel\IdeAutoCompletion\IdeAutoCompletionGenerator;
use Unit\Spryker\Zed\Kernel\IdeAutoCompletion\Fixtures\BundleNameFinder;
use Unit\Spryker\Zed\Kernel\IdeAutoCompletion\Fixtures\MethodTagBuilder;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group IdeAutoCompletion
 * @group IdeAutoCompletionGeneratorTest
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

    /**
     * @return string
     */
    public function getGeneratedFileContent()
    {
        if ($this->generatedFileContent === null) {
            $options = $this->getOptions();

            $generator = new IdeAutoCompletionGenerator($options);

            $generator->create();

            $interfaceName = $this->getOptions('')[IdeAutoCompletionGenerator::OPTION_KEY_INTERFACE_NAME];
            $this->pathToFile = $this->baseDir . 'test/' . $interfaceName . '.php';
            $this->generatedFileContent = file_get_contents($this->pathToFile);
        }

        return $this->generatedFileContent;
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function testFileShouldBeGenerated()
    {
        $this->getGeneratedFileContent();

        $this->assertFileExists($this->pathToFile);
    }

    /**
     * @return void
     */
    public function testFileShouldContainNamespace()
    {
        $generatedFile = $this->getGeneratedFileContent();
        $namespace = $this->getOptions('')[IdeAutoCompletionGenerator::OPTION_KEY_NAMESPACE];

        $this->assertContains('namespace ' . $namespace . ';', $generatedFile);
    }

    /**
     * @return void
     */
    public function testAddMethodTagBuilderShouldReturnGenerator()
    {
        $generator = new IdeAutoCompletionGenerator($this->getOptions());
        $generator = $generator->addMethodTagBuilder(new MethodTagBuilder());

        $this->assertInstanceOf('Spryker\Zed\Kernel\IdeAutoCompletion\IdeAutoCompletionGenerator', $generator);
    }

    /**
     * @return void
     */
    public function testGetNameShouldReturnNameOfGenerator()
    {
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
