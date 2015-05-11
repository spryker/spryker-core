<?php

namespace SprykerEngine\Zed\Transfer\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\TransferBusiness;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use SprykerEngine\Zed\Transfer\Business\Model\TransferGenerator;
use SprykerEngine\Zed\Transfer\TransferConfig;

/**
 * @method TransferBusiness getFactory()
 * @method TransferConfig getConfig()
 */
class TransferDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @param MessengerInterface $messenger
     *
     * @return TransferGenerator
     */
    public function createTransferGenerator(MessengerInterface $messenger)
    {
        return $this->getFactory()->createModelTransferGenerator(
            $messenger,
            $this->createClassGenerator(),
            $this->getConfig()->getSourceDirectories()
        );
    }

    /**
     * @return ClassGenerator
     */
    private function createClassGenerator()
    {
        return $this->getFactory()->createModelGeneratorClassGenerator(
            $this->getConfig()->getTargetDirectory()
        );
    }

    public function createTransferCleaner()
    {
        return $this->getFactory()->createModelTransferCleaner(
            $this->getConfig()->getTargetDirectory()
        );
    }

}
