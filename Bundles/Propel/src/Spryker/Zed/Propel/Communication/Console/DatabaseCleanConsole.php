<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Exception;
use Propel\Runtime\Connection\Exception\ConnectionException;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class DatabaseCleanConsole extends Console
{
    public const COMMAND_NAME = 'propel:database:clean';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Clean existing database.');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Clean propel database');

        try {
            $this->getFacade()->cleanDatabase();
            $this->info('Database cleaned.');
        } catch (ConnectionException $exception) {
            $this->error('Database is not reachable.');
        } catch (Exception $exception) {
            $this->error('Error happened during cleaning.');
            $this->error($exception->getMessage());
        }
    }
}
