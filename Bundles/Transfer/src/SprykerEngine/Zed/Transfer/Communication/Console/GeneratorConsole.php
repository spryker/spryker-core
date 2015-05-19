<?php

namespace SprykerEngine\Zed\Transfer\Communication\Console;

use SprykerEngine\Zed\Transfer\Business\TransferFacade;
use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratorConsole extends Console
{

    const COMMAND_NAME = 'transfer:generate';

    protected function configure()
    {
        parent::configure();
        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $facade = $this->getFacade();
        $messenger = $this->getMessenger();

        $facade->deleteGeneratedTransferObjects();
        $facade->generateTransferObjects($messenger);
        $facade->generateTransferInterfaces($messenger);
    }

    /**
     * @return TransferFacade
     */
    private function getFacade()
    {
        return $this->getLocator()->transfer()->facade();
    }

}
