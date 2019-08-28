<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class VirtualFilesystemHelper extends Module
{
    protected const ASSERT_EXISTS_DIR_MESSAGE = 'Virtual directory "%s" doesn\'t exist';

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $virtualDirectory;

    /**
     * @param array $structure
     *
     * @return string
     */
    public function getVirtualDirectory(array $structure = []): string
    {
        return $this->getVirtualRootDirectory($structure)->url() . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $path
     *
     * @return string[]
     */
    public function getVirtualDirectoryContents(string $path): array
    {
        $contents = [];

        /** @var \org\bovigo\vfs\vfsStreamContent $child */
        foreach ($this->getVirtualRootDirectory()->getChild($path)->getChildren() as $child) {
            $contents[] = $child->getName();
        }

        return $contents;
    }

    /**
     * @param string $path
     * @param string $message
     *
     * @return void
     */
    public function assertVirtualDirectoryIsEmpty(string $path, string $message = ''): void
    {
        $this->assertVirtualDirectoryExists($path, sprintf(static::ASSERT_EXISTS_DIR_MESSAGE, $path));
        $this->assertEmpty($this->getVirtualDirectoryContents($path), $message);
    }

    /**
     * @param string $path
     * @param string $message
     *
     * @return void
     */
    public function assertVirtualDirectoryNotEmpty(string $path, string $message = ''): void
    {
        $this->assertVirtualDirectoryExists($path, sprintf(static::ASSERT_EXISTS_DIR_MESSAGE, $path));
        $this->assertNotEmpty($this->getVirtualDirectoryContents($path), $message);
    }

    /**
     * @param string $path
     * @param string $message
     *
     * @return void
     */
    public function assertVirtualDirectoryExists(string $path, string $message = ''): void
    {
        $this->assertTrue($this->getVirtualRootDirectory()->hasChild($path), $message);
    }

    /**
     * @param array $structure
     *
     * @return \org\bovigo\vfs\vfsStreamDirectory
     */
    protected function getVirtualRootDirectory(array $structure = []): vfsStreamDirectory
    {
        if (!$this->virtualDirectory) {
            $this->virtualDirectory = vfsStream::setup('root', null, $structure);
        }

        return $this->virtualDirectory;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->virtualDirectory = null;
    }
}
