<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Transfer\Business\Model\GeneratedTransferDirectory;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinition;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionBuilder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionFinder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use Spryker\Zed\Transfer\Business\Model\TransferCleaner;
use Spryker\Zed\Transfer\Business\Model\TransferGenerator;
use Spryker\Zed\Transfer\Business\Model\TransferValidator;
use Spryker\Zed\Transfer\TransferDependencyProvider;

/**
 * @method \Spryker\Zed\Transfer\TransferConfig getConfig()
 */
class TransferBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return \Spryker\Zed\Transfer\Business\Model\TransferGeneratorInterface
     */
    public function createTransferGenerator(LoggerInterface $messenger)
    {
        return new TransferGenerator(
            $messenger,
            $this->createClassGenerator(),
            $this->createTransferDefinitionBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\GeneratorInterface
     */
    protected function createClassGenerator()
    {
        return new ClassGenerator(
            $this->getConfig()->getClassTargetDirectory()
        );
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface
     */
    protected function createTransferDefinitionBuilder()
    {
        return new TransferDefinitionBuilder(
            $this->createLoader(),
            $this->createTransferDefinitionMerger(),
            $this->createClassDefinition()
        );
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\LoaderInterface
     */
    protected function createLoader()
    {
        return new TransferDefinitionLoader(
            $this->createFinder(),
            $this->createDefinitionNormalizer()
        );
    }

    /**
     * @deprecated Use createTransferGeneratedDirectory() instead
     *
     * @return \Spryker\Zed\Transfer\Business\Model\TransferCleanerInterface
     */
    public function createTransferCleaner()
    {
        return new TransferCleaner(
            $this->getConfig()->getClassTargetDirectory()
        );
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\GeneratedTransferDirectoryInterface
     */
    public function createTransferGeneratedDirectory()
    {
        return new GeneratedTransferDirectory(
            $this->getConfig()->getClassTargetDirectory(),
            $this->getFileSystem(),
            $this->getFinder()
        );
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function getFileSystem()
    {
        return $this->getProvidedDependency(TransferDependencyProvider::SYMFONY_FILE_SYSTEM);
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder()
    {
        return $this->getProvidedDependency(TransferDependencyProvider::SYMFONY_FINDER);
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\MergerInterface
     */
    protected function createTransferDefinitionMerger()
    {
        return new TransferDefinitionMerger();
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinitionInterface
     */
    protected function createClassDefinition()
    {
        return new ClassDefinition();
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizerInterface
     */
    protected function createDefinitionNormalizer()
    {
        return new DefinitionNormalizer();
    }

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return \Spryker\Zed\Transfer\Business\Model\TransferValidatorInterface
     */
    public function createValidator(LoggerInterface $messenger)
    {
        return new TransferValidator(
            $messenger,
            $this->createFinder()
        );
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface
     */
    protected function createFinder()
    {
        return new TransferDefinitionFinder(
            $this->getConfig()->getSourceDirectories()
        );
    }

}
