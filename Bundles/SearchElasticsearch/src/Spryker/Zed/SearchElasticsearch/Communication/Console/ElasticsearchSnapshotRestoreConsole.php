<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchElasticsearch\Communication\SearchElasticsearchCommunicationFactory getFactory()
 */
class ElasticsearchSnapshotRestoreConsole extends Console
{
    public const COMMAND_NAME = 'elasticsearch:snapshot:restore';
    public const COMMAND_ALIAS = 'search:snapshot:restore';
    public const DESCRIPTION = 'This command will restore an Elasticsearch snapshot.';

    public const ARGUMENT_SNAPSHOT_REPOSITORY = 'snapshot-repository';
    public const ARGUMENT_SNAPSHOT_NAME = 'snapshot-name';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);
        $this->setAliases([static::COMMAND_ALIAS]);

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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $snapshotRepository = $input->getArgument(static::ARGUMENT_SNAPSHOT_REPOSITORY);
        $snapshotName = $input->getArgument(static::ARGUMENT_SNAPSHOT_NAME);

        if (!$this->getFacade()->existsSnapshot($snapshotRepository, $snapshotName)) {
            $this->info(sprintf('Snapshot "%s/%s" does not exist.', $snapshotRepository, $snapshotName));

            return static::CODE_SUCCESS;
        }

        if ($this->getFacade()->restoreSnapshot($snapshotRepository, $snapshotName)) {
            $this->info(sprintf('Snapshot "%s/%s" restored.', $snapshotRepository, $snapshotName));

            return static::CODE_SUCCESS;
        }

        $this->error(sprintf('Snapshot "%s/%s" could not be restored.', $snapshotRepository, $snapshotName));

        return static::CODE_ERROR;
    }
}
