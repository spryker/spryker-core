<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Communication\Console;

use Generated\Shared\Transfer\PushNotificationSubscriptionCollectionDeleteCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface getFacade()
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface getRepository()
 * @method \Spryker\Zed\PushNotification\Communication\PushNotificationCommunicationFactory getFactory()
 */
class DeleteExpiredPushNotificationSubscriptionConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'push-notification:delete-expired-push-notification-subscriptions';

    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'Delete expired push notification subscriptions from Persistence.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getFacade()->deletePushNotificationSubscriptionCollection(
            (new PushNotificationSubscriptionCollectionDeleteCriteriaTransfer())
                ->setIsExpired(true),
        );

        return static::CODE_SUCCESS;
    }
}
