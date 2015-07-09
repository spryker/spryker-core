<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\TransferBusiness;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassGenerator;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\TransferDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface\TransferInterfaceDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\TransferCleaner;
use SprykerEngine\Zed\Transfer\Business\Model\TransferGenerator;
use SprykerEngine\Zed\Transfer\TransferConfig;
use Psr\Log\LoggerInterface;

/**
 * @method TransferBusiness getFactory()
 * @method TransferConfig getConfig()
 */
class TransferDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @param MessengerInterface $messenger
     *
     * @return TransferGenerator
     */
    public function createTransferGenerator(LoggerInterface $messenger)
    {
        return $this->getFactory()->createModelTransferGenerator(
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
        return $this->getFactory()->createModelGeneratorTransferClassGenerator(
            $this->getConfig()->getClassTargetDirectory()
        );
    }

    /**
     * @return TransferDefinitionBuilder|DefinitionBuilderInterface
     */
    private function createTransferDefinitionBuilder()
    {
        return $this->getFactory()->createModelGeneratorTransferTransferDefinitionBuilder(
            $this->createLoader(),
            $this->getFactory()->createModelGeneratorTransferDefinitionMerger(),
            $this->getFactory()->createModelGeneratorTransferClassDefinition()
        );
    }

    /**
     * @return TransferDefinitionLoader
     */
    private function createLoader()
    {
        return $this->getFactory()->createModelGeneratorTransferDefinitionLoader(
            $this->getFactory()->createModelGeneratorDefinitionNormalizer(),
            $this->getConfig()->getSourceDirectories()
        );
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return TransferGenerator
     */
    public function createTransferInterfaceGenerator(LoggerInterface $messenger)
    {
        return $this->getFactory()->createModelTransferGenerator(
            $messenger,
            $this->createInterfaceGenerator(),
            $this->createInterfaceDefinitionBuilder()
        );
    }

    /**
     * @return ClassGenerator
     */
    private function createInterfaceGenerator()
    {
        return $this->getFactory()->createModelGeneratorTransferInterfaceInterfaceGenerator(
            $this->getConfig()->getGeneratedTargetDirectory()
        );
    }

    /**
     * @return TransferInterfaceDefinitionBuilder|DefinitionBuilderInterface
     */
    private function createInterfaceDefinitionBuilder()
    {
        return $this->getFactory()->createModelGeneratorTransferInterfaceTransferInterfaceDefinitionBuilder(
            $this->createLoader(),
            $this->getFactory()->createModelGeneratorTransferInterfaceInterfaceDefinition()
        );
    }

    /**
     * @return TransferCleaner
     */
    public function createTransferCleaner()
    {
        return $this->getFactory()->createModelTransferCleaner(
            $this->getConfig()->getGeneratedTargetDirectory()
        );
    }

}
