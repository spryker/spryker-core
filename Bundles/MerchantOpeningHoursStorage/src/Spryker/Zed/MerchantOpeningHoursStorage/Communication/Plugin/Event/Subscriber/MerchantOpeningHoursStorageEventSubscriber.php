<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantOpeningHours\Dependency\MerchantOpeningHoursEvents;
use Spryker\Zed\MerchantOpeningHoursStorage\Communication\Plugin\Event\Listener\MerchantOpeningHoursDateScheduleStoragePublishListener;
use Spryker\Zed\MerchantOpeningHoursStorage\Communication\Plugin\Event\Listener\MerchantOpeningHoursScheduleStoragePublishListener;
use Spryker\Zed\MerchantOpeningHoursStorage\Communication\Plugin\Event\Listener\MerchantOpeningHoursWeekdayScheduleStoragePublishListener;

/**
 * @deprecated Use {@link \MerchantOpeningHoursStoragePublisherPlugin} instead
 *
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Business\MerchantOpeningHoursStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Communication\MerchantOpeningHoursStorageCommunicationFactory getFactory()
 */
class MerchantOpeningHoursStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addMerchantOpeningHoursPublishListener($eventCollection)
            ->addMerchantOpeningHoursWeekdayScheduleCreateListener($eventCollection)
            ->addMerchantOpeningHoursDateScheduleCreateListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addMerchantOpeningHoursPublishListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(MerchantOpeningHoursEvents::MERCHANT_OPENING_HOURS_PUBLISH, new MerchantOpeningHoursScheduleStoragePublishListener(), 0, null, $this->getConfig()->getEventQueueName());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addMerchantOpeningHoursWeekdayScheduleCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(MerchantOpeningHoursEvents::ENTITY_SPY_MERCHANT_OPENING_HOURS_WEEKDAY_SCHEDULE_CREATE, new MerchantOpeningHoursWeekdayScheduleStoragePublishListener(), 0, null, $this->getConfig()->getEventQueueName());

        return $this;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return $this
     */
    protected function addMerchantOpeningHoursDateScheduleCreateListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(MerchantOpeningHoursEvents::ENTITY_SPY_MERCHANT_OPENING_HOURS_DATE_SCHEDULE_CREATE, new MerchantOpeningHoursDateScheduleStoragePublishListener(), 0, null, $this->getConfig()->getEventQueueName());

        return $this;
    }
}
