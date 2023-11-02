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
 * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Communication\Console\ElasticsearchSnapshotDeleteConsole} instead.
 *
 * @method \Spryker\Zed\Search\Business\SearchFacadeInterface getFacade()
 * @method \Spryker\Zed\Search\Communication\SearchCommunicationFactory getFactory()
 */
class SearchDeleteSnapshotConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'search:snapshot:delete';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command will delete a snapshot if it exists.';

    /**
     * @var string
     */
    public const ARGUMENT_SNAPSHOT_REPOSITORY = 'snapshot-repository';

    /**
     * @var string
     */
    public const ARGUMENT_SNAPSHOT_NAME = 'snapshot-name';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

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
        /** @var string $snapshotRepository */
        $snapshotRepository = $input->getArgument(static::ARGUMENT_SNAPSHOT_REPOSITORY);
        /** @var string $snapshotName */
        $snapshotName = $input->getArgument(static::ARGUMENT_SNAPSHOT_NAME);

        if (!$this->getFacade()->existsSnapshot($snapshotRepository, $snapshotName)) {
            $this->info(sprintf('Snapshot "%s/%s" does not exist.', $snapshotRepository, $snapshotName));

            return static::CODE_SUCCESS;
        }

        if ($this->getFacade()->deleteSnapshot($snapshotRepository, $snapshotName)) {
            $this->info(sprintf('Snapshot "%s/%s" deleted.', $snapshotRepository, $snapshotName));

            return static::CODE_SUCCESS;
        }

        $this->error(sprintf('Snapshot "%s/%s" could not be deleted.', $snapshotRepository, $snapshotName));

        return static::CODE_ERROR;
    }
}
