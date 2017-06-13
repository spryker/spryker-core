<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Communication\Plugin;

use Codeception\TestCase\Test;
use Spryker\Zed\DataImport\Communication\Plugin\DataImportConsoleTimerEventSubscriberPlugin;
use Spryker\Zed\Event\Dependency\EventCollection;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Communication
 * @group Plugin
 * @group DataImportConsoleTimerEventSubscriberPluginTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\CommunicationTester $tester
 */
class DataImportConsoleTimerEventSubscriberPluginTest extends Test
{

    const EXPECTED_SUBSCRIBED_EVENT_COUNT = 6;

    /**
     * @return void
     */
    public function testGetSubscribedEventsAddsListenerToEvents()
    {
        $eventCollection = new EventCollection();
        $dataImportTimerDebugEventSubscriberPlugin = new DataImportConsoleTimerEventSubscriberPlugin();
        $dataImportTimerDebugEventSubscriberPlugin->getSubscribedEvents($eventCollection);

        $this->assertCount(self::EXPECTED_SUBSCRIBED_EVENT_COUNT, $eventCollection, sprintf(
            'Expected "%s" subscribed events.',
            static::EXPECTED_SUBSCRIBED_EVENT_COUNT
        ));
    }

}
