<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Search\Business\SearchFacade getFacade()
 */
class SearchDeleteSnapshotConsole extends Console
{

    const COMMAND_NAME = 'search:snapshot:delete';
    const DESCRIPTION = 'This command will delete a snapshot if it exists.';

    const ARGUMENT_SNAPSHOT_REPOSITORY = 'snapshot-repository';
    const ARGUMENT_SNAPSHOT_NAME = 'snapshot-name';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addArgument(static::ARGUMENT_SNAPSHOT_REPOSITORY, InputArgument::REQUIRED, 'Name of the snapshot repository.');
        $this->addArgument(static::ARGUMENT_SNAPSHOT_NAME, InputArgument::REQUIRED, 'Name of the snapshot.');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $snapshotRepository = $input->getArgument(static::ARGUMENT_SNAPSHOT_REPOSITORY);
        $snapshotName = $input->getArgument(static::ARGUMENT_SNAPSHOT_NAME);

        if (!$this->getFacade()->existsSnapshot($snapshotRepository, $snapshotName)) {
            $this->info(sprintf('Snapshot "%s/%s" does not exist.', $snapshotRepository, $snapshotName));

            return static::CODE_SUCCESS;
        }

        $this->info(sprintf('Deleting snapshot "%s/%s"', $snapshotRepository, $snapshotName));
        if ($this->getFacade()->deleteSnapshot($snapshotRepository, $snapshotName)) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }

}
