<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Console;

use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Business\CustomerBusinessFactory getFactory()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 */
class CustomerPasswordSetConsole extends Console
{
    protected const COMMAND_NAME = 'customer:password:set';
    protected const OPTION_FORCE = 'force';
    protected const OPTION_FORCE_SHORT = 'f';
    protected const OPTION_NO_TOKEN = 'no-token';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Sends the forgot password email to all customers that have an empty password inside the database.')
            ->addOption(static::OPTION_FORCE, static::OPTION_FORCE_SHORT, InputOption::VALUE_NONE, 'Forced execution.')
            ->addOption(static::OPTION_NO_TOKEN, null, InputOption::VALUE_NONE, 'Option to send the email to all customers that do not have a token to reset the password.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $customerCriteriaFilterTransfer = $this->createCustomerCriteriaFilterTransfer($input->getOption(static::OPTION_NO_TOKEN));
        $customerCollection = $this->getFacade()->getCustomerCollectionByCriteria($customerCriteriaFilterTransfer);

        if (!$input->getOption(static::OPTION_FORCE)) {
            if (!$this->getQuestionHelper()->ask($input, $output, $this->createConfirmationQuestion($customerCollection->getCustomers()->count()))) {
                return static::CODE_SUCCESS;
            }
        }

        $this->getFacade()->sendPasswordRestoreMailForCustomerCollection($customerCollection, $output);

        return static::CODE_SUCCESS;
    }

    /**
     * @param int $customersCount
     *
     * @return \Symfony\Component\Console\Question\ConfirmationQuestion
     */
    protected function createConfirmationQuestion(int $customersCount): ConfirmationQuestion
    {
        return new ConfirmationQuestion(
            sprintf('%s customers in the database will be affected. Do you want to continue? [Y/n]', $customersCount),
            false
        );
    }

    /**
     * @param bool $noToken
     *
     * @return \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer
     */
    protected function createCustomerCriteriaFilterTransfer(bool $noToken): CustomerCriteriaFilterTransfer
    {
        return (new CustomerCriteriaFilterTransfer())
            ->setRestorePasswordKeyExists(!$noToken)
            ->setPasswordExists(false);
    }
}
