<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion;

class FileWriter implements FileWriterInterface
{
    /**
     * @param string $fileName
     * @param string $fileContent
     * @param array $options
     *
     * @return void
     */
    public function writeFile(string $fileName, string $fileContent, array $options): void
    {
        $targetDirectory = $this->getTargetDirectory($options);

        $this->makeDirIfNotExists($targetDirectory, $options);

        file_put_contents($targetDirectory . $fileName, $fileContent);
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getTargetDirectory(array $options): string
    {
        $baseDirectory = rtrim(
            $options[IdeAutoCompletionOptionConstants::TARGET_BASE_DIRECTORY],
            DIRECTORY_SEPARATOR
        );

        $applicationPathFragment = trim(
            str_replace(
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER,
                $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME],
                $options[IdeAutoCompletionOptionConstants::TARGET_DIRECTORY_PATTERN]
            ),
            DIRECTORY_SEPARATOR
        );

        return "{$baseDirectory}/{$applicationPathFragment}/";
    }

    /**
     * @param string $directory
     * @param array $options
     *
     * @return void
     */
    protected function makeDirIfNotExists(string $directory, array $options): void
    {
        if (!is_dir($directory)) {
            mkdir($directory, $options[IdeAutoCompletionConstants::DIRECTORY_PERMISSION], true);
        }
    }
}
