<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Quote\Business\QuoteFacadeInterface getFacade()
 * @method \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface getRepository()
 */
class DeleteExpiredGuestQuoteConsole extends Console
{
    protected const COMMAND_NAME = 'quote:delete-expired-guest-quotes';
    protected const COMMAND_DESCRIPTION = 'Delete all expired guest quotes.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::COMMAND_DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFacade()->deleteExpiredGuestQuote();
    }
}
