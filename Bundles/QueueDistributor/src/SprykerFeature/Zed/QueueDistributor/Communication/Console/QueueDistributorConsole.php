<?php

namespace SprykerFeature\Zed\QueueDistributor\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\QueueDistributor\Business\QueueDistributorDependencyContainer;
use SprykerFeature\Zed\QueueDistributor\Business\QueueDistributorFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method QueueDistributorDependencyContainer getDependencyContainer()
 * @method QueueDistributorFacade getFacade()
 */
class QueueDistributorConsole extends Console
{
    const COMMAND_NAME = 'distributor:distribute-items';
    const COMMAND_DESCRIPTION = 'distribute items';
    const TYPE = 'type';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::COMMAND_DESCRIPTION);
        $this->addOption(self::TYPE);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
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
        $itemType = $input->getOption(self::TYPE);

        if (false !== $itemType) {
            $itemTypes[] = $itemType;
        }

        return $itemTypes;
    }
}
