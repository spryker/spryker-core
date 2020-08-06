<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Communication\Console;

use Exception;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Oauth\Business\OauthFacadeInterface getFacade()
 * @method \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface getRepository()
 */
class OauthTokenConsole extends Console
{
    public const COMMAND_NAME = 'oauth:refresh-token:remove-expired';
    public const DESCRIPTION = 'Remove expired refresh tokens from the database.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<fg=yellow>----------------------------------------</fg=yellow>');
        $output->writeln('<fg=yellow>Remove expired refresh tokens from the database</fg=yellow>');
        $output->writeln('');

        try {
            $deleteCount = $this->getFacade()->deleteExpiredRefreshTokens();
        } catch (Exception $exception) {
            $this->error('Error happened during deleting expired refresh tokens.');
            $this->error($exception->getMessage());

            return static::CODE_ERROR;
        }

        $output->writeln(sprintf('<fg=white>Removed %s expired refresh tokens </fg=white>', $deleteCount));
        $output->writeln('');
        $output->writeln('<fg=green>Finished. All Done.</fg=green>');

        return static::CODE_SUCCESS;
    }
}
