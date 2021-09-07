<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\GeneratedFileFinder;

use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractTransferFileFinder implements GeneratedFileFinderInterface
{
    /**
     * @var string
     */
    protected const TRANSFER_NAMESPACE_PATTERN = 'Generated\Shared\Transfer\%s';

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param string $directoryPath
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function findFiles(string $directoryPath): Finder
    {
        $finder = clone $this->finder;
        $finder->in($directoryPath)
            ->depth(0)
            ->filter(function (SplFileInfo $fileEntry) {
                return $this->filterTransferFileEntry($fileEntry);
            });

        return $finder;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileEntry
     *
     * @return bool
     */
    protected function filterTransferFileEntry(SplFileInfo $fileEntry): bool
    {
        $filename = $fileEntry->getFilename();
        $filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);

        $transferClassName = $this->buildFullyQualifiedTransferClassName(
            $filenameWithoutExtension
        );

        return $this->extendsExpectedBaseClass($transferClassName);
    }

    /**
     * @param string $transferFileName
     *
     * @return string
     */
    protected function buildFullyQualifiedTransferClassName(string $transferFileName): string
    {
        return sprintf(static::TRANSFER_NAMESPACE_PATTERN, $transferFileName);
    }

    /**
     * @param string $transferClassName
     *
     * @return string|null
     */
    protected function getTransferParentClassName(string $transferClassName): ?string
    {
        if (!class_exists($transferClassName)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($transferClassName);

        return $reflectionClass->getParentClass()
            ? $reflectionClass->getParentClass()->getName()
            : null;
    }

    /**
     * @param string $transferClassName
     *
     * @return bool
     */
    protected function extendsExpectedBaseClass(string $transferClassName): bool
    {
        $parentClassName = $this->getTransferParentClassName($transferClassName);

        return $parentClassName === $this->getBaseClassToMatch();
    }

    /**
     * @return string
     */
    abstract protected function getBaseClassToMatch(): string;
}
