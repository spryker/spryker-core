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
use function _HumbugBox3c32a251b752\Amp\Promise\rethrow;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Business\CustomerBusinessFactory getFactory()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 */
class CustomerResetPassword extends Console
{
    public const COMMAND_NAME = 'customer:password:reset';
    public const OPTION_FORCE = 'force';
    public const OPTION_FORCE_SHORT = 'f';
    public const OPTION_NO_TOKEN = 'no-token';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Sending the forgot password email to all customers inside the database')
            ->addOption(self::OPTION_FORCE, self::OPTION_FORCE_SHORT, InputOption::VALUE_NONE, 'Forced execution')
            ->addOption(self::OPTION_NO_TOKEN, null, InputOption::VALUE_NONE, 'Option to send the email to all customers that do not have a token to reset the password');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $customerCriteriaFilterTransfer = $this->prepareCustomerCriteriaFilterTransfer($input->getOption(self::OPTION_NO_TOKEN));

        if (!$input->getOption(self::OPTION_FORCE)) {
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
            ->getCustomerCollectionTransferByCriteriaFilterTransfer($customerCriteriaFilterTransfer);

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
        return (new CustomerCriteriaFilterTransfer())
            ->setRestorePasswordKeyExists($noToken ? false : true);
    }
}
