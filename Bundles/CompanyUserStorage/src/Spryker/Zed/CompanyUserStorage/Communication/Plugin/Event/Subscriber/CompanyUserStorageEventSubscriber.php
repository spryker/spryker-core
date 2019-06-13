<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Company\Dependency\CompanyEvents;
use Spryker\Zed\CompanyUser\Dependency\CompanyUserEvents;
use Spryker\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener\CompanyUserCompanyStoragePublishListener;
use Spryker\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener\CompanyUserStoragePublishListener;
use Spryker\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener\CompanyUserStorageUnpublishListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUserStorage\CompanyUserStorageConfig getConfig()
 * @method \Spryker\Zed\CompanyUserStorage\Business\CompanyUserStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUserStorage\Communication\CompanyUserStorageCommunicationFactory getFactory()
 */
class CompanyUserStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addCompanyUserPublishListener($eventCollection);
        $this->addCompanyUserUnpublishListener($eventCollection);
        $this->addCompanyUserCreateListener($eventCollection);
        $this->addCompanyUserUpdateListener($eventCollection);
        $this->addCompanyUserDeleteListener($eventCollection);
        $this->addCompanyUpdateListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCompanyUserPublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            CompanyUserEvents::COMPANY_USER_PUBLISH,
            new CompanyUserStoragePublishListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCompanyUserUnpublishListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            CompanyUserEvents::COMPANY_USER_UNPUBLISH,
            new CompanyUserStorageUnpublishListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCompanyUserCreateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            CompanyUserEvents::ENTITY_SPY_COMPANY_USER_CREATE,
            new CompanyUserStoragePublishListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCompanyUserUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            CompanyUserEvents::ENTITY_SPY_COMPANY_USER_UPDATE,
            new CompanyUserStoragePublishListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCompanyUserDeleteListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            CompanyUserEvents::ENTITY_SPY_COMPANY_USER_DELETE,
            new CompanyUserStorageUnpublishListener()
        );
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addCompanyUpdateListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            CompanyEvents::ENTITY_SPY_COMPANY_UPDATE,
            new CompanyUserCompanyStoragePublishListener()
        );
    }
}
