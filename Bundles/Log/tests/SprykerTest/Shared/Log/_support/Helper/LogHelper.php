<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Log\Helper;

use Codeception\Module;
use SprykerTest\Shared\Testify\Helper\VirtualFilesystemHelper;

class LogHelper extends Module
{
    /**
     * @param string $fileName
     *
     * @return void
     */
    public function assertLogFileExists(string $fileName): void
    {
        $pathToLogFile = $this->getPathToLogFile($fileName);

        $this->assertTrue(file_exists($pathToLogFile), sprintf('Expected file "%s" does not exists!', $fileName));
    }

    /**
     * @param string $fileName
     * @param string $expectedContent
     *
     * @return void
     */
    public function assertLogFileContains(string $fileName, string $expectedContent): void
    {
        $this->assertLogFileExists($fileName);
        $pathToLogFile = $this->getPathToLogFile($fileName);
        $logFileContent = file_get_contents($pathToLogFile);

        $this->assertStringContainsString($expectedContent, $logFileContent, sprintf('Expected content "%s" was not found in "%s"!', $expectedContent, $fileName));
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getPathToLogFile(string $fileName): string
    {
        return $this->getVirtualFilesystemHelper()->getVirtualDirectory() . $fileName;
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\VirtualFilesystemHelper
     */
    private function getVirtualFilesystemHelper(): VirtualFilesystemHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\VirtualFilesystemHelper $virtualFilesystemHelper */
        $virtualFilesystemHelper = $this->getModule('\\' . VirtualFilesystemHelper::class);

        return $virtualFilesystemHelper;
    }
}
