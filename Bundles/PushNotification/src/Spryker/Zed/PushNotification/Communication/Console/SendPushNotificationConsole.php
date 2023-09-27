<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Communication\Console;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationCriteriaTransfer;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface getFacade()
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface getRepository()
 * @method \Spryker\Zed\PushNotification\Communication\PushNotificationCommunicationFactory getFactory()
 * @method \Spryker\Zed\PushNotification\PushNotificationConfig getConfig()
 */
class SendPushNotificationConsole extends Console
{
    use BundleConfigResolverAwareTrait;

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
        $pushNotificationFacade = $this->getFacade();

        $pushNotificationCollectionTransfer = $pushNotificationFacade->getPushNotificationCollection(
            $this->createPushNotificationCriteriaTransfer(),
        );

        $pushNotificationCollectionResponseTransfer = $pushNotificationFacade->sendPushNotifications(
            (new PushNotificationCollectionRequestTransfer())->setPushNotifications($pushNotificationCollectionTransfer->getPushNotifications()),
        );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
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

    /**
     * @return \Generated\Shared\Transfer\PushNotificationCriteriaTransfer
     */
    protected function createPushNotificationCriteriaTransfer(): PushNotificationCriteriaTransfer
    {
        $paginationTransfer = (new PaginationTransfer())
            ->setOffset(0)
            ->setLimit($this->getConfig()->getPushNotificationSendBatchSize());

        $pushNotificationConditionsTransfer = (new PushNotificationConditionsTransfer())
            ->setNotificationSent(false);

        return (new PushNotificationCriteriaTransfer())
            ->setPushNotificationConditions($pushNotificationConditionsTransfer)
            ->setPagination($paginationTransfer);
    }
}
