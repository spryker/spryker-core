<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion;

use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleFinderInterface;

class IdeAutoCompletionWriter implements IdeAutoCompletionWriterInterface
{
    /**
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\GeneratorInterface[]
     */
    protected $generators;

    /**
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleFinderInterface
     */
    protected $bundleFinder;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Generator\GeneratorInterface[] $generators
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleFinderInterface $bundleFinder
     * @param array $options
     */
    public function __construct(array $generators, BundleFinderInterface $bundleFinder, array $options)
    {
        $this->generators = $generators;
        $this->bundleFinder = $bundleFinder;
        $this->options = $options;
    }

    /**
     * @return void
     */
    public function writeCompletionFiles()
    {
        $bundleTransferCollection = $this->bundleFinder->find();

        foreach ($this->generators as $generator) {
            $fileContent = $generator->generate($bundleTransferCollection);

            $this->saveFile($generator->getName(), $fileContent);
        }
    }

    /**
     * @param string $generatorName
     * @param string $fileContent
     *
     * @return void
     */
    protected function saveFile($generatorName, $fileContent)
    {
        $targetDirectory = $this->getTargetDirectory();

        $this->makeDirIfNotExists($targetDirectory);

        $fileName = "{$generatorName}.php";

        file_put_contents($targetDirectory . $fileName, $fileContent);
    }

    /**
     * @return string
     */
    protected function getTargetDirectory()
    {
        $baseDirectory = rtrim(
            $this->options[IdeAutoCompletionOptionConstants::TARGET_BASE_DIRECTORY],
            DIRECTORY_SEPARATOR
        );

        $applicationPathFragment = trim(
            str_replace(
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER,
                $this->options[IdeAutoCompletionOptionConstants::APPLICATION_NAME],
                $this->options[IdeAutoCompletionOptionConstants::TARGET_DIRECTORY_PATTERN]
            ),
            DIRECTORY_SEPARATOR
        );

        return "{$baseDirectory}/{$applicationPathFragment}/";
    }

    /**
     * @param string $directory
     *
     * @return void
     */
    protected function makeDirIfNotExists($directory)
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
}
