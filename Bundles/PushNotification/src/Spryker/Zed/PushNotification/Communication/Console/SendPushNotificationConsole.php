<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface getFacade()
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface getRepository()
 * @method \Spryker\Zed\PushNotification\Communication\PushNotificationCommunicationFactory getFactory()
 */
class SendPushNotificationConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'push-notification:send-push-notifications';

    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'Sends notifications in an async way.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_TEMPLATE = '<error>Failed to send PushNotification %s: %s</error>';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $pushNotificationCollectionResponseTransfer = $this->getFacade()->sendPushNotifications();

        /**
         * @var \ArrayObject<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
         */
        $errorTransfers = $pushNotificationCollectionResponseTransfer->getErrors();

        if ($errorTransfers->count() === 0) {
            return static::CODE_SUCCESS;
        }

        foreach ($errorTransfers as $errorTransfer) {
            $output->writeln(
                sprintf(
                    static::ERROR_MESSAGE_TEMPLATE,
                    $errorTransfer->getEntityIdentifierOrFail(),
                    $errorTransfer->getMessageOrFail(),
                ),
            );
        }

        return static::CODE_ERROR;
    }
}
