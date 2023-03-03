<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Reader;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PushNotificationGroupConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationGroupCriteriaTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface;

class PushNotificationGroupReader implements PushNotificationGroupReaderInterface
{
    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface
     */
    protected PushNotificationRepositoryInterface $pushNotificationRepository;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface $pushNotificationRepository
     */
    public function __construct(PushNotificationRepositoryInterface $pushNotificationRepository)
    {
        $this->pushNotificationRepository = $pushNotificationRepository;
    }

    /**
     * @param string $name
     * @param string|null $identifier
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupTransfer|null
     */
    public function findPushNotificationGroupByNameAndIdentifier(string $name, ?string $identifier): ?PushNotificationGroupTransfer
    {
        $pushNotificationGroupConditionsTransfer = (new PushNotificationGroupConditionsTransfer())
            ->addName($name);
        if ($identifier) {
            $pushNotificationGroupConditionsTransfer->addIdentifier($identifier);
        }

        $paginationTransfer = (new PaginationTransfer())
            ->setPage(1)
            ->setMaxPerPage(1);

        $pushNotificationGroupCriteriaTransfer = (new PushNotificationGroupCriteriaTransfer())
            ->setPushNotificationGroupConditions($pushNotificationGroupConditionsTransfer)
            ->setPagination($paginationTransfer);

        $pushNotificationGroupCollectionTransfer = $this
            ->pushNotificationRepository
            ->getPushNotificationGroupCollection(
                $pushNotificationGroupCriteriaTransfer,
            );

        $groupTransfers = $pushNotificationGroupCollectionTransfer->getGroups();

        return $groupTransfers->count() ? $groupTransfers->offsetGet(0) : null;
    }
}
