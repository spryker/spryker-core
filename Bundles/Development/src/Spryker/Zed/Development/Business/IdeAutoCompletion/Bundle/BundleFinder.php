<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle;

use ArrayIterator;
use ArrayObject;
use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;
use InvalidArgumentException;
use Symfony\Component\Finder\Finder;

class BundleFinder implements BundleFinderInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilderInterface
     */
    protected $bundleBuilder;

    /**
     * @var array
     */
    protected $directoryGlobPatterns;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilderInterface $bundleBuilder
     * @param array $directoryGlobPatterns
     */
    public function __construct(Finder $finder, BundleBuilderInterface $bundleBuilder, array $directoryGlobPatterns)
    {
        $this->finder = $finder;
        $this->bundleBuilder = $bundleBuilder;
        $this->directoryGlobPatterns = $directoryGlobPatterns;
    }

    /**
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[]
     */
    public function find()
    {
        $bundleTransferCollection = [];

        foreach ($this->directoryGlobPatterns as $baseDirectoryGlobPattern => $namespaceDirectoryFragmentGlobPattern) {
            $bundleDirectoryGlobPattern = $baseDirectoryGlobPattern . $namespaceDirectoryFragmentGlobPattern;

            foreach ($this->getBundleDirectories($bundleDirectoryGlobPattern) as $bundleDirectory) {
                $bundleTransfer = $this->bundleBuilder->buildFromDirectory($baseDirectoryGlobPattern, $bundleDirectory);
                $bundleTransfer = $this->mergeWithPossibleExistingBundle($bundleTransfer, $bundleTransferCollection);

                $bundleTransferCollection[$bundleTransfer->getName()] = $bundleTransfer;
            }
        }

        return $bundleTransferCollection;
    }

    /**
     * @param string $bundleDirectoryGlobPattern
     *
     * @return \Traversable|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getBundleDirectories($bundleDirectoryGlobPattern)
    {
        try {
            $directories = $this
                ->getFinder()
                ->directories()
                ->in($bundleDirectoryGlobPattern)
                ->depth('== 0');
        } catch (InvalidArgumentException $exception) {
            return new ArrayIterator();
        }

        return $directories;
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder()
    {
        return clone $this->finder;
    }

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[] $bundles
     *
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer
     */
    protected function mergeWithPossibleExistingBundle(IdeAutoCompletionBundleTransfer $bundleTransfer, array $bundles)
    {
        if (!array_key_exists($bundleTransfer->getName(), $bundles)) {
            return $bundleTransfer;
        }

        $existingBundleTransfer = $bundles[$bundleTransfer->getName()];

        $mergedMethodTransferCollection = $this->mergeMethods(
            $existingBundleTransfer->getMethods(),
            $bundleTransfer->getMethods()
        );
        $bundleTransfer->setMethods($mergedMethodTransferCollection);

        return $bundleTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\IdeAutoCompletionBundleMethodTransfer[] $existingMethodTransferCollection
     * @param \ArrayObject|\Generated\Shared\Transfer\IdeAutoCompletionBundleMethodTransfer[] $methodTransferCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\IdeAutoCompletionBundleMethodTransfer[]
     */
    protected function mergeMethods(
        ArrayObject $existingMethodTransferCollection,
        ArrayObject $methodTransferCollection
    ) {
        $methodsByName = [];

        foreach ($existingMethodTransferCollection as $methodTransfer) {
            $methodsByName[$methodTransfer->getName()] = $methodTransfer;
        }

        foreach ($methodTransferCollection as $methodTransfer) {
            $methodsByName[$methodTransfer->getName()] = $methodTransfer;
        }

        $mergedMethodTransferCollection = new ArrayObject();
        foreach ($methodsByName as $methodTransfer) {
            $mergedMethodTransferCollection->append($methodTransfer);
        }

        return $mergedMethodTransferCollection;
    }
}
