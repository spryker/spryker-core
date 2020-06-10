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
class CustomerSetPassword extends Console
{
    protected const COMMAND_NAME = 'customer:password:set';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Sending the forgot password email to all customers that have an empty password inside the database')
            ->addOption('--force', '-f', InputOption::VALUE_NONE, 'Forced execution')
            ->addOption('--no-token', null, InputOption::VALUE_NONE, 'Option to send the email to all customers that do not have a token to reset the password');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $customerCriteriaFilterTransfer =
            $this->prepareCustomerCriteriaFilterTransfer($input->getOption('no-token'));

        if (!$input->getOption('force')) {
            $helper = $this->getHelper('question');

            $customersCount = $this->getFacade()->getCustomersForResetPasswordCount($customerCriteriaFilterTransfer);

            $question = new ConfirmationQuestion(
                sprintf('%s customers in the database will be affected. Do you want to continue? [Y/n]', $customersCount),
                false
            );

            if (!$helper->ask($input, $output, $question)) {
                return 0;
            }
        }

        $customerCollection = $this->getFacade()
            ->findCustomersByCriteriaFilterTransfer($customerCriteriaFilterTransfer);

        $this->getFacade()->sendPasswordRestoreMailForCustomerCollection($customerCollection);

        return static::CODE_SUCCESS;
    }

    /**
     * @param bool $noToken
     *
     * @return \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer
     */
    private function prepareCustomerCriteriaFilterTransfer(bool $noToken): CustomerCriteriaFilterTransfer
    {
        $customerCriteriaFilterTransfer = new CustomerCriteriaFilterTransfer();
        $customerCriteriaFilterTransfer
            ->setRestorePasswordKeyExist($noToken ? false : true)
            ->setPasswordExist(false);

        return $customerCriteriaFilterTransfer;
    }
}
