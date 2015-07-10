<?php

namespace SprykerFeature\Zed\Distributor\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\Distributor\Business\DistributorDependencyContainer;
use SprykerFeature\Zed\Distributor\Business\DistributorFacade;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method DistributorDependencyContainer getDependencyContainer()
 * @method DistributorFacade getFacade()
 */
class DistributorConsole extends Console
{

    const COMMAND_NAME = 'distributor:distribute-items';
    const COMMAND_DESCRIPTION = 'distribute items';
    const TYPE = 'type';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::COMMAND_DESCRIPTION);
        $this->addArgument(self::TYPE, InputArgument::OPTIONAL);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $itemTypes = $this->getItemTypes($input);
        $this->getFacade()->distributeItems($this->getMessenger(), $itemTypes);
    }

    /**
     * @param InputInterface $input
     *
     * @return mixed
     */
    protected function getItemTypes(InputInterface $input)
    {
        $itemTypes = [];
        $itemType = $input->getArgument(self::TYPE);

        if (null !== $itemType) {
            $itemTypes[] = $itemType;
        }

        return $itemTypes;
    }

}
