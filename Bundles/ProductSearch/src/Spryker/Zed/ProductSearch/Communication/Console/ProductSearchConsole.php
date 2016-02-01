<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Spryker\Zed\ProductSearch\Business\ProductSearchFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method ProductSearchFacade getFacade()
 */
class ProductSearchConsole extends Console
{

    const COMMAND_NAME = 'product:search';
    const DESCRIPTION = 'This command will run installer for product search';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFacade()->install($this->getMessenger());
    }

}
