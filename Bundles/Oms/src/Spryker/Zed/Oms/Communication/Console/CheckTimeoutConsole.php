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
    /**
     * @var string
     */
    public const COMMAND_NAME = 'oms:check-timeout';

    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'Check timeouts';

    /**
     * @var string
     */
    protected const OPTION_STORE_NAME = 'store-name';

    /**
     * @var string
     */
    protected const OPTION_STORE_NAME_SHORT = 's';

    /**
     * @var string
     */
    protected const OPTION_LIMIT = 'limit';

    /**
     * @var string
     */
    protected const OPTION_LIMIT_SHORT = 'l';

    /**
     * @var string
     */
    protected const OPTION_PROCESSOR_ID = 'processor-id';

    /**
     * @var string
     */
    protected const OPTION_PROCESSOR_ID_SHORT = 'p';

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->addOption(static::OPTION_STORE_NAME, static::OPTION_STORE_NAME_SHORT, InputOption::VALUE_REQUIRED, 'Defines the store name for which order item timeouts should be checked.')
            ->addOption(static::OPTION_LIMIT, static::OPTION_LIMIT_SHORT, InputOption::VALUE_REQUIRED, 'Defines the amount of orders for which the order item timeouts should be checked.')
            ->addOption(static::OPTION_PROCESSOR_ID, static::OPTION_PROCESSOR_ID_SHORT, InputOption::VALUE_OPTIONAL, 'Defines coma-separated list of the processor identifiers in a multi-thread OMS setup.');

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

        /** @var string|null $storeName */
        $storeName = $input->getOption(static::OPTION_STORE_NAME);

        if ($storeName) {
            $omsCheckTimeoutsQueryCriteriaTransfer->setStoreName($storeName);
        }

        if ($input->getOption(static::OPTION_LIMIT)) {
            $omsCheckTimeoutsQueryCriteriaTransfer->setLimit((int)$input->getOption(static::OPTION_LIMIT));
        }

        $omsProcessorIdentifiers = $this->getOmsProcessorIdentifiers($input);

        if ($omsProcessorIdentifiers) {
            $omsCheckTimeoutsQueryCriteriaTransfer->setOmsProcessorIdentifiers($omsProcessorIdentifiers);
        }

        return $omsCheckTimeoutsQueryCriteriaTransfer;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return array<int>
     */
    protected function getOmsProcessorIdentifiers(InputInterface $input): array
    {
        /** @var string|null $processorIdentifiers */
        $processorIdentifiers = $input->getOption(static::OPTION_PROCESSOR_ID);
        if (!$processorIdentifiers) {
            return [];
        }

        $omsProcessorIdentifiers = explode(',', $processorIdentifiers);

        return array_map('intval', $omsProcessorIdentifiers);
    }
}
