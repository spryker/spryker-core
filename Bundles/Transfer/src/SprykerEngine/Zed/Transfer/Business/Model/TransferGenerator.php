<?php

namespace SprykerEngine\Zed\Transfer\Business\Model;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\GeneratorInterface;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionBuilder;

class TransferGenerator
{

    /**
     * @var ClassGenerator
     */
    private $generator;

    /**
     * @var TransferDefinitionBuilder
     */
    private $transferDefinitionBuilder;

    /**
     * @param MessengerInterface $messenger
     * @param GeneratorInterface $generator
     * @param TransferDefinitionBuilder $transferDefinitionBuilder
     */
    public function __construct(MessengerInterface $messenger, GeneratorInterface $generator, TransferDefinitionBuilder $transferDefinitionBuilder)
    {
        $this->messenger = $messenger;
        $this->generator = $generator;
        $this->transferDefinitionBuilder = $transferDefinitionBuilder;
    }

    public function execute()
    {
        $definitions = $this->transferDefinitionBuilder->getTransferDefinitions();

        foreach ($definitions as $classDefinition) {
            $fileName = $this->generator->generate($classDefinition);

            $this->messenger->info(sprintf('<info>%s</info> was generated', $fileName));
        }
    }

}
