<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder;

use Generated\Shared\Transfer\IdeAutoCompletionBundleMethodTransfer;
use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractorInterface;
use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractBundleMethodBuilder implements BundleMethodBuilderInterface
{
    public const FILE_EXTENSION = 'php';

    /**
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractorInterface
     */
    protected $namespaceExtractor;

    /**
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractorInterface $namespaceExtractor
     */
    public function __construct(NamespaceExtractorInterface $namespaceExtractor)
    {
        $this->namespaceExtractor = $namespaceExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     *
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleMethodTransfer|null
     */
    public function getMethod(IdeAutoCompletionBundleTransfer $bundleTransfer)
    {
        $file = $this->findFile($bundleTransfer);

        if (!$file) {
            return null;
        }

        $bundleMethodTransfer = new IdeAutoCompletionBundleMethodTransfer();
        $bundleMethodTransfer->setName($this->getMethodName());
        $bundleMethodTransfer->setClassName($this->getClassNameFromFile($file));
        $bundleMethodTransfer->setNamespaceName($this->getNamespace($file, $bundleTransfer));

        return $bundleMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     *
     * @return \Symfony\Component\Finder\SplFileInfo|null
     */
    protected function findFile(IdeAutoCompletionBundleTransfer $bundleTransfer)
    {
        $searchDirectory = $this->getSearchDirectory($bundleTransfer);

        if (!$this->isSearchDirectoryAccessible($searchDirectory)) {
            return null;
        }

        $file = $this->findInterfaceFile($bundleTransfer, $searchDirectory);
        if ($file) {
            return $file;
        }

        $file = $this->findClassFile($bundleTransfer, $searchDirectory);

        return $file;
    }

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     *
     * @return string
     */
    abstract protected function getSearchDirectory(IdeAutoCompletionBundleTransfer $bundleTransfer);

    /**
     * @param string $searchDirectory
     *
     * @return bool
     */
    protected function isSearchDirectoryAccessible($searchDirectory)
    {
        return is_dir($searchDirectory);
    }

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     * @param string $searchDirectory
     *
     * @return \Symfony\Component\Finder\SplFileInfo|null
     */
    protected function findInterfaceFile(IdeAutoCompletionBundleTransfer $bundleTransfer, $searchDirectory)
    {
        $interfaceFileName = $this->getInterfaceFileName($bundleTransfer->getName());

        return $this->findFileByName($interfaceFileName, $searchDirectory);
    }

    /**
     * @param string $bundleName
     *
     * @return string
     */
    protected function getInterfaceFileName($bundleName)
    {
        return sprintf('%s%sInterface.%s', $bundleName, ucfirst($this->getMethodName()), static::FILE_EXTENSION);
    }

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     * @param string $searchDirectory
     *
     * @return \Symfony\Component\Finder\SplFileInfo|null
     */
    protected function findClassFile(IdeAutoCompletionBundleTransfer $bundleTransfer, $searchDirectory)
    {
        $classFileName = $this->getClassFileName($bundleTransfer->getName());

        return $this->findFileByName($classFileName, $searchDirectory);
    }

    /**
     * @param string $bundleName
     *
     * @return string
     */
    protected function getClassFileName($bundleName)
    {
        return sprintf('%s%s.%s', $bundleName, ucfirst($this->getMethodName()), static::FILE_EXTENSION);
    }

    /**
     * @param string $fileName
     * @param string $searchPath
     *
     * @return \Symfony\Component\Finder\SplFileInfo|null
     */
    protected function findFileByName($fileName, $searchPath)
    {
        $filePath = $searchPath . $fileName;

        if (file_exists($filePath)) {
            return new SplFileInfo($filePath, dirname($filePath), basename($filePath));
        }

        return null;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $file
     *
     * @return string
     */
    protected function getClassNameFromFile(SplFileInfo $file)
    {
        return str_replace('.' . static::FILE_EXTENSION, '', $file->getFilename());
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $file
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     *
     * @return string
     */
    protected function getNamespace(SplFileInfo $file, IdeAutoCompletionBundleTransfer $bundleTransfer)
    {
        $directory = $this->getFileDirectory($file);

        if (!$directory) {
            return '';
        }

        return $this->namespaceExtractor->fromDirectory($directory, $bundleTransfer->getBaseDirectory());
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $file
     *
     * @return \Symfony\Component\Finder\SplFileInfo|null
     */
    protected function getFileDirectory(SplFileInfo $file)
    {
        $classFileDirectory = str_replace($file->getFilename(), '', $file->getPath());

        return new SplFileInfo($classFileDirectory, $file->getRelativePath(), $file->getFilename());
    }
}
