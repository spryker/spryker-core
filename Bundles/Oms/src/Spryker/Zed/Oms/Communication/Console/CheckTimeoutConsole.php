<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Console;

use Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Oms\Communication\OmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface getRepository()
 */
class CheckTimeoutConsole extends Console
{
    public const COMMAND_NAME = 'oms:check-timeout';
    public const COMMAND_DESCRIPTION = 'Check timeouts';
    protected const OPTION_STORE_NAME = 'store-name';
    protected const OPTION_STORE_NAME_SHORT = 's';
    protected const OPTION_LIMIT = 'limit';
    protected const OPTION_LIMIT_SHORT = 'l';

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addOption(static::OPTION_STORE_NAME, static::OPTION_STORE_NAME_SHORT, InputOption::VALUE_REQUIRED, 'Defines the store name for which order item timeouts should be checked.')
            ->addOption(static::OPTION_LIMIT, static::OPTION_LIMIT_SHORT, InputOption::VALUE_REQUIRED, 'Defines the amount of orders for which the order item timeouts should be checked.');

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
        $omsCheckTimeoutsQueryCriteriaTransfer = $this->buildOmsCheckTimeoutsQueryCriteriaTransfer($input);

        $this->getFacade()->checkTimeouts([], $omsCheckTimeoutsQueryCriteriaTransfer);

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \Generated\Shared\Transfer\OmsCheckTimeoutsQueryCriteriaTransfer
     */
    protected function buildOmsCheckTimeoutsQueryCriteriaTransfer(InputInterface $input): OmsCheckTimeoutsQueryCriteriaTransfer
    {
        $omsCheckTimeoutsQueryCriteriaTransfer = new OmsCheckTimeoutsQueryCriteriaTransfer();

        if ($input->getOption(static::OPTION_STORE_NAME)) {
            $omsCheckTimeoutsQueryCriteriaTransfer->setStoreName($input->getOption(static::OPTION_STORE_NAME));
        }

        if ($input->getOption(static::OPTION_LIMIT)) {
            $omsCheckTimeoutsQueryCriteriaTransfer->setLimit((int)$input->getOption(static::OPTION_LIMIT));
        }

        return $omsCheckTimeoutsQueryCriteriaTransfer;
    }
}
