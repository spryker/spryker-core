<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder;

use Generated\Shared\Transfer\IdeAutoCompletionBundleMethodTransfer;
use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;
use Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractorInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractBundleMethodBuilder implements BundleMethodBuilderInterface
{

    const FILE_EXTENSION = 'php';

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractorInterface
     */
    protected $namespaceExtractor;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\NamespaceExtractorInterface $namespaceExtractor
     */
    public function __construct(Finder $finder, NamespaceExtractorInterface $namespaceExtractor)
    {
        $this->finder = $finder;
        $this->namespaceExtractor = $namespaceExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     *
     * @return null|\Generated\Shared\Transfer\IdeAutoCompletionBundleMethodTransfer
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
        $bundleMethodTransfer->setNamespace($this->getNamespace($file, $bundleTransfer));

        return $bundleMethodTransfer;
    }

    /**
     * @return string
     */
    abstract protected function getMethodName();

    /**
     * @param string $bundleDirectory
     *
     * @return string
     */
    abstract protected function getSearchPathGlobPattern($bundleDirectory);

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     *
     * @return null|\Symfony\Component\Finder\SplFileInfo
     */
    protected function findFile(IdeAutoCompletionBundleTransfer $bundleTransfer)
    {
        $searchPathGlobPattern = $this->getSearchPathGlobPattern($bundleTransfer->getDirectory());

        $interfaceFileName = $this->getInterfaceFileName($bundleTransfer->getName());
        $file = $this->findFileByName($interfaceFileName, $searchPathGlobPattern);

        if ($file) {
            return $file;
        }

        $classFileName = $this->getClassFileName($bundleTransfer->getName());
        $file = $this->findFileByName($classFileName, $searchPathGlobPattern);

        return $file;
    }

    /**
     * @param string $bundleName
     *
     * @return string
     */
    protected function getInterfaceFileName($bundleName)
    {
        return sprintf(
            '%s%sInterface.%s',
            $bundleName,
            ucfirst($this->getMethodName()),
            static::FILE_EXTENSION
        );
    }

    /**
     * @param string $bundleName
     *
     * @return string
     */
    protected function getClassFileName($bundleName)
    {
        return sprintf(
            '%s%s.%s',
            $bundleName,
            ucfirst($this->getMethodName()),
            static::FILE_EXTENSION
        );
    }

    /**
     * @param string $fileName
     * @param string $searchPathGlobPattern
     *
     * @return null|\Symfony\Component\Finder\SplFileInfo
     */
    protected function findFileByName($fileName, $searchPathGlobPattern)
    {
        try {
            $files = $this
                ->getFinder()
                ->files()
                ->in($searchPathGlobPattern)
                ->name($fileName);
        } catch (\InvalidArgumentException $exception) {
            return null;
        }

        foreach ($files as $file) {
            return $file;
        }

        return null;
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder()
    {
        return clone $this->finder;
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
     * @return null|\Symfony\Component\Finder\SplFileInfo
     */
    protected function getFileDirectory(SplFileInfo $file)
    {
        $classFileDirectory = str_replace($file->getFilename(), '', $file->getPath());

        return new SplFileInfo($classFileDirectory, null, null);
    }

}
