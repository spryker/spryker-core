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
class SearchReindexConsole extends Console
{

    const COMMAND_NAME = 'search:reindex';
    const DESCRIPTION = 'This command will reindex the search';

    const ARGUMENT_SOURCE = 'source';
    const ARGUMENT_DESTINATION = 'destination';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addArgument(static::ARGUMENT_SOURCE, InputArgument::REQUIRED, 'Path to source.');
        $this->addArgument(static::ARGUMENT_DESTINATION, InputArgument::REQUIRED, 'Path to destination.');

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
        $source = $input->getArgument(static::ARGUMENT_SOURCE);
        $destination = $input->getArgument(static::ARGUMENT_DESTINATION);

        $body = sprintf('{"source": {"index": "%s"}, "dest": {"index": "%s"}}', $source, $destination);
        $client = new Client();
        $response = $client->post('localhost:10005/_reindex?pretty', [
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

}
