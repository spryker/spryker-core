<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Communication\Console;

use GuzzleHttp\Client;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Search\Business\SearchFacade getFacade()
 */
class SearchRegisterSnapshotRepositoryConsole extends Console
{

    const COMMAND_NAME = 'search:snapshot:register-repository';
    const DESCRIPTION = 'This command will register a snapshot repository';

    const ARGUMENT_SNAPSHOT_REPOSITORY = 'snapshot-repository';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addArgument(static::ARGUMENT_SNAPSHOT_REPOSITORY, InputArgument::REQUIRED, 'Name of the snapshot repository.');

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
        $snapshotRepository = $input->getArgument(static::ARGUMENT_SNAPSHOT_REPOSITORY);

        $doesSnapshotRepositoryExists = $this->existSnapshotRepository($snapshotRepository);

        if ($doesSnapshotRepositoryExists) {
            $this->info(sprintf('Snapshot repository "%s" already exists.', $snapshotRepository));

            return static::CODE_SUCCESS;
        }

        $body = sprintf('{"type": "fs", "settings": {"location": "%s"}}', $snapshotRepository);

        $client = new Client();
        $response = $client->put('localhost:10005/_snapshot/' . $snapshotRepository, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $body,
        ]);

        if ($response->getStatusCode() === 200) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }

    /**
     * @param string $snapshotRepository
     *
     * @return bool
     */
    protected function existSnapshotRepository($snapshotRepository)
    {
        $client = new Client();
        $response = $client->get('localhost:10005/_snapshot/_all');
        if ($response->getStatusCode() === 200) {
            $snapshots = json_decode($response->getBody()->getContents(), true);
            if (isset($snapshots[$snapshotRepository])) {
                return true;
            }
        }

        return false;
    }

}
