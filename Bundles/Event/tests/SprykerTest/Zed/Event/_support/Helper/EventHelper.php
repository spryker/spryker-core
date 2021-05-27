<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Event\Helper;

use Codeception\TestInterface;
use Spryker\Zed\Event\Business\Subscriber\SubscriberMerger;
use Spryker\Zed\Event\Dependency\EventSubscriberCollection;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Event\EventDependencyProvider;
use Spryker\Zed\Publisher\Communication\Plugin\Event\PublisherSubscriber;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;
use SprykerTest\Shared\Testify\Helper\StaticVariablesHelper;
use SprykerTest\Zed\Testify\Helper\Business\DependencyProviderHelperTrait;

class EventHelper extends AbstractHelper
{
    use DependencyProviderHelperTrait;
    use StaticVariablesHelper;

    /**
     * @var bool
     */
    protected $isInitialized = false;

    /**
     * @var array
     */
    protected $eventSubscriber = [];

    /**
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface $eventSubscriber
     *
     * @return void
     */
    public function addEventSubscriber(EventSubscriberInterface $eventSubscriber): void
    {
        $this->eventSubscriber[get_class($eventSubscriber)] = $eventSubscriber;

        $eventSubscriberCollection = new EventSubscriberCollection();

        foreach ($this->eventSubscriber as $eventSubscriber) {
            $eventSubscriberCollection->add($eventSubscriber);
        }

        $this->getDependencyProviderHelper()->setDependency(EventDependencyProvider::EVENT_SUBSCRIBERS, $eventSubscriberCollection);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->cleanupStaticCache(SubscriberMerger::class, 'eventCollectionBuffer', null);

        $this->addEventSubscriber(new PublisherSubscriber());
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->resetStaticCaches();
    }
}
