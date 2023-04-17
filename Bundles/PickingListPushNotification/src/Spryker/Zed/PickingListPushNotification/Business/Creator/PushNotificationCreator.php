<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business\Creator;

use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;
use Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer;
use Generated\Shared\Transfer\PushNotificationGroupTransfer;
use Generated\Shared\Transfer\PushNotificationProviderTransfer;
use Generated\Shared\Transfer\PushNotificationTransfer;
use Spryker\Zed\PickingListPushNotification\Business\Filter\PickingListFilterInterface;
use Spryker\Zed\PickingListPushNotification\Business\Generator\PushNotificationPayloadGeneratorInterface;
use Spryker\Zed\PickingListPushNotification\Business\Grouper\PickingListGrouperInterface;
use Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToPushNotificationFacadeInterface;
use Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig;

class PushNotificationCreator implements PushNotificationCreatorInterface
{
    /**
     * @var \Spryker\Zed\PickingListPushNotification\Business\Filter\PickingListFilterInterface
     */
    protected PickingListFilterInterface $pickingListFilter;

    /**
     * @var \Spryker\Zed\PickingListPushNotification\Business\Grouper\PickingListGrouperInterface
     */
    protected PickingListGrouperInterface $pickingListGrouper;

    /**
     * @var \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig
     */
    protected PickingListPushNotificationConfig $pickingListPushNotificationConfig;

    /**
     * @var \Spryker\Zed\PickingListPushNotification\Business\Generator\PushNotificationPayloadGeneratorInterface
     */
    protected PushNotificationPayloadGeneratorInterface $pushNotificationPayloadGenerator;

    /**
     * @var \Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToPushNotificationFacadeInterface
     */
    protected PickingListPushNotificationToPushNotificationFacadeInterface $pushNotificationFacade;

    /**
     * @param \Spryker\Zed\PickingListPushNotification\Business\Filter\PickingListFilterInterface $pickingListFilter
     * @param \Spryker\Zed\PickingListPushNotification\Business\Grouper\PickingListGrouperInterface $pickingListGrouper
     * @param \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig $pickingListPushNotificationConfig
     * @param \Spryker\Zed\PickingListPushNotification\Business\Generator\PushNotificationPayloadGeneratorInterface $pushNotificationPayloadGenerator
     * @param \Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToPushNotificationFacadeInterface $pushNotificationFacade
     */
    public function __construct(
        PickingListFilterInterface $pickingListFilter,
        PickingListGrouperInterface $pickingListGrouper,
        PickingListPushNotificationConfig $pickingListPushNotificationConfig,
        PushNotificationPayloadGeneratorInterface $pushNotificationPayloadGenerator,
        PickingListPushNotificationToPushNotificationFacadeInterface $pushNotificationFacade
    ) {
        $this->pickingListFilter = $pickingListFilter;
        $this->pickingListGrouper = $pickingListGrouper;
        $this->pickingListPushNotificationConfig = $pickingListPushNotificationConfig;
        $this->pushNotificationPayloadGenerator = $pushNotificationPayloadGenerator;
        $this->pushNotificationFacade = $pushNotificationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function createPushNotificationCollection(
        PushNotificationCollectionRequestTransfer $pushNotificationCollectionRequestTransfer
    ): PickingListCollectionResponseTransfer {
        $pickingListTransfers = $this->pickingListFilter->filterNotifiablePickingLists(
            $pushNotificationCollectionRequestTransfer->getPickingLists(),
        );

        $pickingListCollectionResponseTransfer = (new PickingListCollectionResponseTransfer())
            ->setPickingLists($pushNotificationCollectionRequestTransfer->getPickingLists());
        if (!$pickingListTransfers->count()) {
            return $pickingListCollectionResponseTransfer;
        }

        $pickingListTransfersGroupedByWarehouseUuid = $this
            ->pickingListGrouper
            ->groupPickingListsByWarehouseUuid($pickingListTransfers);

        $pushNotificationCollectionRequestTransfer = $this->createPushNotificationCollectionRequestTransfer(
            $pickingListTransfersGroupedByWarehouseUuid,
            $pushNotificationCollectionRequestTransfer->getActionOrFail(),
        );

        $pushNotificationCollectionResponseTransfer = $this->pushNotificationFacade->createPushNotificationCollection(
            $pushNotificationCollectionRequestTransfer,
        );

        return $pickingListCollectionResponseTransfer
            ->setErrors($pushNotificationCollectionResponseTransfer->getErrors());
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     * @param string $action
     *
     * @return \Generated\Shared\Transfer\PushNotificationTransfer
     */
    protected function createPushNotificationTransfer(
        array $pickingListTransfers,
        string $action
    ): PushNotificationTransfer {
        return (new PushNotificationTransfer())
            ->setProvider($this->createPushNotificationProviderTransfer())
            ->setGroup($this->createPushNotificationGroupTransfer($pickingListTransfers))
            ->setPayload(
                $this->pushNotificationPayloadGenerator->generatePushNotificationPayload($pickingListTransfers, $action),
            );
    }

    /**
     * @return \Generated\Shared\Transfer\PushNotificationProviderTransfer
     */
    protected function createPushNotificationProviderTransfer(): PushNotificationProviderTransfer
    {
        return (new PushNotificationProviderTransfer())
            ->setName($this->pickingListPushNotificationConfig->getPushNotificationProviderName());
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return \Generated\Shared\Transfer\PushNotificationGroupTransfer
     */
    protected function createPushNotificationGroupTransfer(array $pickingListTransfers): PushNotificationGroupTransfer
    {
        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        $pickingListTransfer = $pickingListTransfers[0];

        return (new PushNotificationGroupTransfer())
            ->setName($this->pickingListPushNotificationConfig->getPushNotificationWarehouseGroup())
            ->setIdentifier($pickingListTransfer->getWarehouseOrFail()->getUuidOrFail());
    }

    /**
     * @param array<string, array<int, \Generated\Shared\Transfer\PickingListTransfer>> $pickingListTransfersGroupedByWarehouseUuid
     * @param string $action
     *
     * @return \Generated\Shared\Transfer\PushNotificationCollectionRequestTransfer
     */
    protected function createPushNotificationCollectionRequestTransfer(
        array $pickingListTransfersGroupedByWarehouseUuid,
        string $action
    ): PushNotificationCollectionRequestTransfer {
        $pushNotificationCollectionRequestTransfer = (new PushNotificationCollectionRequestTransfer())
            ->setIsTransactional(true);
        foreach ($pickingListTransfersGroupedByWarehouseUuid as $pickingListTransfers) {
            $pushNotificationCollectionRequestTransfer->addPushNotification(
                $this->createPushNotificationTransfer($pickingListTransfers, $action),
            );
        }

        return $pushNotificationCollectionRequestTransfer;
    }
}
