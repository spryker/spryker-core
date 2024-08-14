<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Console;

use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\Console\StoreAwareConsole;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 */
class CustomerPasswordResetConsole extends StoreAwareConsole
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'customer:password:reset';

    /**
     * @var string
     */
    protected const OPTION_FORCE = 'force';

    /**
     * @var string
     */
    protected const OPTION_FORCE_SHORT = 'f';

    /**
     * @var string
     */
    protected const OPTION_NO_TOKEN = 'no-token';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STORE = 'Option store should be provided for Dynamic Store environment.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Sends the forgot password email using a freshly generated password restore key to all customers filtered by criteria using command options.')
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
        /** @var bool $noToken */
        $noToken = $input->getOption(static::OPTION_NO_TOKEN);
        $customerCriteriaFilterTransfer = $this->createCustomerCriteriaFilterTransfer($noToken);
        $customerCollection = $this->getFacade()->getCustomerCollectionByCriteria($customerCriteriaFilterTransfer);

        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if ($this->getFactory()->getStoreFacade()->isDynamicStoreEnabled() === true) {
            $storeName = $this->getStore($input);
            if ($storeName === null) {
                $this->error(static::ERROR_MESSAGE_STORE);

                return static::CODE_ERROR;
            }
            foreach ($customerCollection->getCustomers() as $customer) {
                $customer->setStoreName($storeName);
            }
        }

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
            false,
        );
    }

    /**
     * @param bool $noToken
     *
     * @return \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer
     */
    protected function createCustomerCriteriaFilterTransfer(bool $noToken): CustomerCriteriaFilterTransfer
    {
        return (new CustomerCriteriaFilterTransfer())->setRestorePasswordKeyExists(!$noToken);
    }
}
