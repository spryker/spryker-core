<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassDefinition;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use Generated\Zed\Ide\FactoryAutoCompletion\TransferBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassGenerator;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\TransferDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use SprykerEngine\Zed\Transfer\Business\Model\TransferCleaner;
use SprykerEngine\Zed\Transfer\Business\Model\TransferGenerator;
use SprykerEngine\Zed\Transfer\TransferConfig;
use Psr\Log\LoggerInterface;

/**
 * @method TransferConfig getConfig()
 */
class TransferDependencyContainer extends AbstractBusinessDependencyContainer
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
            new TransferDefinitionMerger(),
            new ClassDefinition()
        );
    }

    /**
     * @return TransferDefinitionLoader
     */
    private function createLoader()
    {
        return new TransferDefinitionLoader(
            new DefinitionNormalizer(),
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

}
