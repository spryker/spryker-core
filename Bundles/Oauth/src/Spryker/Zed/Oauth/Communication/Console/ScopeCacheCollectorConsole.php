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
class ScopeCacheCollectorConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'oauth:scope-collection-file:generate';

    /**
     * @var string
     */
    public const DESCRIPTION = 'Create cache file for collect all existing scopes';

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
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=yellow>----------------------------------------</fg=yellow>');
        $output->writeln('<fg=yellow>Scopes collecting in progress</fg=yellow>');
        $output->writeln('');

        try {
            $this->getFacade()->generateScopeCollection();
        } catch (Exception $exception) {
            $this->error('Error happened collecting scopes.');
            $this->error($exception->getMessage());

            return static::CODE_ERROR;
        }

        $output->writeln('<fg=green>Finished. All Done.</fg=green>');

        return static::CODE_SUCCESS;
    }
}
