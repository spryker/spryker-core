<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Transfer\Business;

use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use Spryker\Zed\Transfer\Business\Model\Generator\Transfer\ClassDefinition;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface;
use Spryker\Zed\Transfer\Business\Model\Generator\Transfer\ClassGenerator;
use Spryker\Zed\Transfer\Business\Model\Generator\Transfer\TransferDefinitionBuilder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use Spryker\Zed\Transfer\Business\Model\TransferCleaner;
use Spryker\Zed\Transfer\Business\Model\TransferGenerator;
use Spryker\Zed\Transfer\TransferConfig;
use Psr\Log\LoggerInterface;

/**
 * @method TransferConfig getConfig()
 */
class TransferDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @param LoggerInterface $messenger
     *
     * @return TransferGenerator
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
     * @return ClassGenerator
     */
    private function createClassGenerator()
    {
        return new ClassGenerator(
            $this->getConfig()->getClassTargetDirectory()
        );
    }

    /**
     * @return TransferDefinitionBuilder|DefinitionBuilderInterface
     */
    private function createTransferDefinitionBuilder()
    {
        return new TransferDefinitionBuilder(
            $this->createLoader(),
            $this->createTransferDefinitionMerger(),
            $this->createClassDefinition()
        );
    }

    /**
     * @return TransferDefinitionLoader
     */
    private function createLoader()
    {
        return new TransferDefinitionLoader(
            $this->createDefinitionNormalizer(),
            $this->getConfig()->getSourceDirectories()
        );
    }

    /**
     * @return TransferCleaner
     */
    public function createTransferCleaner()
    {
        return new TransferCleaner(
            $this->getConfig()->getGeneratedTargetDirectory()
        );
    }

    /**
     * @return TransferDefinitionMerger
     */
    protected function createTransferDefinitionMerger()
    {
        return new TransferDefinitionMerger();
    }

    /**
     * @return ClassDefinition
     */
    protected function createClassDefinition()
    {
        return new ClassDefinition();
    }

    /**
     * @return DefinitionNormalizer
     */
    protected function createDefinitionNormalizer()
    {
        return new DefinitionNormalizer();
    }

}
