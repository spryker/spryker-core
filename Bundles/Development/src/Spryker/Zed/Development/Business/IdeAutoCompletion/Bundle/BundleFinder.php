<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle;

use Generated\Shared\Transfer\IdeAutoCompletionBundleMethodTransfer;
use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
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
    protected $options;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\BundleBuilderInterface $bundleBuilder
     * @param array $options
     */
    public function __construct(Finder $finder, BundleBuilderInterface $bundleBuilder, array $options)
    {
        $this->finder = $finder;
        $this->bundleBuilder = $bundleBuilder;
        $this->options = $options;
    }

    /**
     * @return \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer[]
     */
    public function find()
    {
        $bundleTransferCollection = [];
        $sourceDirectoryGlobPatterns = $this->getSourceDirectoryGlobPatterns();

        foreach ($sourceDirectoryGlobPatterns as $baseDirectoryGlobPattern => $namespaceDirectoryFragmentGlobPattern) {
            $bundleDirectoryGlobPattern = $this->buildBundleDirectoryGlobPattern(
                $baseDirectoryGlobPattern,
                $namespaceDirectoryFragmentGlobPattern
            );

            foreach ($this->getBundleDirectories($bundleDirectoryGlobPattern) as $bundleDirectory) {
                $bundleTransfer = $this->bundleBuilder->buildFromPath($baseDirectoryGlobPattern, $bundleDirectory);
                $bundleTransfer = $this->mergeWithPossibleExistingBundle($bundleTransfer, $bundleTransferCollection);

                $bundleTransferCollection[$bundleTransfer->getName()] = $bundleTransfer;
            }
        }

        return $bundleTransferCollection;
    }

    /**
     * @return string[]
     */
    protected function getSourceDirectoryGlobPatterns()
    {
        return $this->options[IdeAutoCompletionOptionConstants::SOURCE_DIRECTORY_GLOB_PATTERNS];
    }

    /**
     * @param string $baseDirectoryGlobPattern
     * @param string $namespaceFragmentGlobPattern
     *
     * @return string
     */
    protected function buildBundleDirectoryGlobPattern(
        $baseDirectoryGlobPattern,
        $namespaceFragmentGlobPattern
    ) {
        return str_replace(
            IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER,
            $this->options[IdeAutoCompletionOptionConstants::APPLICATION_NAME],
            $baseDirectoryGlobPattern . $namespaceFragmentGlobPattern
        );
    }

    /**
     * @param string $bundleDirectoryGlobPattern
     *
     * @return \Traversable
     */
    protected function getBundleDirectories($bundleDirectoryGlobPattern)
    {
        try {
            $directories = $this
                ->getFinder()
                ->directories()
                ->in($bundleDirectoryGlobPattern)
                ->depth(0);
        } catch (\InvalidArgumentException $exception) {
            return new \ArrayIterator();
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
     * @param \ArrayObject|IdeAutoCompletionBundleMethodTransfer[] $existingMethodTransferCollection
     * @param \ArrayObject|IdeAutoCompletionBundleMethodTransfer[] $methodTransferCollection
     *
     * @return \ArrayObject|IdeAutoCompletionBundleMethodTransfer[]
     */
    protected function mergeMethods(
        \ArrayObject $existingMethodTransferCollection,
        \ArrayObject $methodTransferCollection
    ) {
        $methodsByName = [];

        foreach ($existingMethodTransferCollection as $methodTransfer) {
            $methodsByName[$methodTransfer->getName()] = $methodTransfer;
        }

        foreach ($methodTransferCollection as $methodTransfer) {
            $methodsByName[$methodTransfer->getName()] = $methodTransfer;
        }

        $mergedMethodTransferCollection = new \ArrayObject();
        foreach ($methodsByName as $methodTransfer) {
            $mergedMethodTransferCollection->append($methodTransfer);
        }

        return $mergedMethodTransferCollection;
    }

}
