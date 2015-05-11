<?php

namespace SprykerEngine\Zed\Transfer\Business\Model;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionBuilder;

class TransferGenerator
{

    /**
     * @var ClassGenerator
     */
    private $classGenerator;

    /**
     * @var TransferDefinitionBuilder
     */
    private $transferDefinitionBuilder;

    /**
     * @param MessengerInterface $messenger
     * @param ClassGenerator $classGenerator
     * @param TransferDefinitionBuilder $transferDefinitionBuilder
     */
    public function __construct(MessengerInterface $messenger, ClassGenerator $classGenerator, TransferDefinitionBuilder $transferDefinitionBuilder)
    {
        $this->messenger = $messenger;
        $this->classGenerator = $classGenerator;
        $this->transferDefinitionBuilder = $transferDefinitionBuilder;
    }

    public function execute()
    {
        $definitions = $this->transferDefinitionBuilder->getTransferDefinitions();

        foreach ($definitions as $classDefinition) {
            $fileName = $this->classGenerator->generateClass($classDefinition);

            $this->messenger->info(sprintf('<info>%s.php</info> was generated', $fileName));
        }
    }

}
