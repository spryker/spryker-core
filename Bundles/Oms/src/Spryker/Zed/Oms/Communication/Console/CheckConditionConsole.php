<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Console;

use Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer;
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
class CheckConditionConsole extends Console
{
    public const COMMAND_NAME = 'oms:check-condition';
    public const COMMAND_DESCRIPTION = 'Check conditions';

    protected const OPTION_STORE_NAME = 'store-name';
    protected const OPTION_STORE_NAME_SHORT = 's';
    protected const OPTION_LIMIT = 'limit';
    protected const OPTION_LIMIT_SHORT = 'l';
    protected const OPTION_PROCESSOR_ID = 'processor-id';
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
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $omsCheckConditionQueryCriteriaTransfer = $this->buildOmsCheckConditionQueryCriteriaTransfer($input);

        $this->getFacade()->checkConditions([], $omsCheckConditionQueryCriteriaTransfer);

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return \Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer
     */
    protected function buildOmsCheckConditionQueryCriteriaTransfer(InputInterface $input): OmsCheckConditionsQueryCriteriaTransfer
    {
        $omsCheckConditionQueryCriteriaTransfer = new OmsCheckConditionsQueryCriteriaTransfer();

        if ($input->getOption(static::OPTION_STORE_NAME)) {
            $omsCheckConditionQueryCriteriaTransfer->setStoreName($input->getOption(static::OPTION_STORE_NAME));
        }

        if ($input->getOption(static::OPTION_LIMIT)) {
            $omsCheckConditionQueryCriteriaTransfer->setLimit((int)$input->getOption(static::OPTION_LIMIT));
        }

        $omsProcessorIdentifiers = $this->getOmsProcessorIdentifiers($input);

        if ($omsProcessorIdentifiers) {
            $omsCheckConditionQueryCriteriaTransfer->setOmsProcessorIdentifiers($omsProcessorIdentifiers);
        }

        return $omsCheckConditionQueryCriteriaTransfer;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return int[]
     */
    protected function getOmsProcessorIdentifiers(InputInterface $input): array
    {
        if (!$input->getOption(static::OPTION_PROCESSOR_ID)) {
            return [];
        }

        $omsProcessorIdentifiers = explode(',', $input->getOption(static::OPTION_PROCESSOR_ID));

        return array_map('intval', $omsProcessorIdentifiers);
    }
}
