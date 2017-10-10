<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\EventBehavior\Business;

use Codeception\Test\Unit;
use DateInterval;
use DateTime;
use Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChange;
use Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChangeQuery;
use Spryker\Shared\Config\Config;
use Spryker\Shared\EventBehavior\EventBehaviorConstants;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\EventBehavior\Business\EventBehaviorBusinessFactory;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacade;
use Spryker\Zed\EventBehavior\Dependency\Facade\EventBehaviorToEventInterface;
use Spryker\Zed\EventBehavior\Dependency\Service\EventBehaviorToUtilEncodingInterface;
use Spryker\Zed\EventBehavior\EventBehaviorConfig;
use Spryker\Zed\EventBehavior\EventBehaviorDependencyProvider;
use Spryker\Zed\EventBehavior\Persistence\Propel\Behavior\EventBehavior;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\RequestIdentifier;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group EventBehavior
 * @group Business
 * @group Facade
 * @group EventBehaviorFacadeTest
 * Add your own group annotations below this line
 */
class EventBehaviorFacadeTest extends Unit
{

    const FOREIGN_KEYS = 'foreign_keys';
    const MODIFIED_COLUMNS = 'modified_columns';

    /**
     * @var \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->cleanupEventMemory();
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testEventBehaviorWillTriggerMemoryEventsData()
    {
        $behaviorStatus = Config::get(EventBehaviorConstants::EVENT_BEHAVIOR_TRIGGERING_ACTIVE, false);
        if (!$behaviorStatus) {
            return;
        }

        $this->createEntityChangeEvent();

        $container = new Container();
        $container[EventBehaviorDependencyProvider::FACADE_EVENT] = function (Container $container) {
            $storageMock = $this->createEventFacadeMockBridge();
            $storageMock->expects($this->once())->method('trigger')->will(
                $this->returnCallback(
                    function ($eventName, TransferInterface $eventTransfer) {
                        $this->assertTriggeredEvent($eventName, $eventTransfer);
                    }
                )
            );

            return $storageMock;
        };

        $container = $this->generateUtilEncodingServiceMock($container);
        $this->prepareFacade($container);
        $this->eventBehaviorFacade->triggerRuntimeEvents();
    }

    /**
     * @return void
     */
    public function testEventBehaviorWillTriggerLostEventsData()
    {
        $behaviorStatus = Config::get(EventBehaviorConstants::EVENT_BEHAVIOR_TRIGGERING_ACTIVE, false);
        if (!$behaviorStatus) {
            return;
        }

        $this->createLostEntityChangeEvent();

        $container = new Container();
        $container[EventBehaviorDependencyProvider::FACADE_EVENT] = function (Container $container) {
            $storageMock = $this->createEventFacadeMockBridge();
            $storageMock->expects($this->once())->method('trigger')->will(
                $this->returnCallback(
                    function ($eventName, TransferInterface $eventTransfer) {
                        $this->assertTriggeredEvent($eventName, $eventTransfer);
                    }
                )
            );

            return $storageMock;
        };

        $container = $this->generateUtilEncodingServiceMock($container);
        $this->prepareFacade($container);
        $this->eventBehaviorFacade->triggerLostEvents();
    }

    /**
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    public function assertTriggeredEvent($eventName, TransferInterface $eventTransfer)
    {
        $this->assertEquals($eventName, 'test');
        $actualArray = $eventTransfer->toArray();

        $actualArray[EventBehavior::EVENT_CHANGE_ENTITY_FOREIGN_KEYS] = $actualArray[self::FOREIGN_KEYS];
        unset($actualArray[self::FOREIGN_KEYS]);

        $actualArray[EventBehavior::EVENT_CHANGE_ENTITY_MODIFIED_COLUMNS] = $actualArray[self::MODIFIED_COLUMNS];
        unset($actualArray[self::MODIFIED_COLUMNS]);

        $this->assertEquals($actualArray, $this->createEventData());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createEventFacadeMockBridge()
    {
        return $this->getMockBuilder(EventBehaviorToEventInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'trigger',
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createUtilEncodingServiceBridge()
    {
        return $this->getMockBuilder(EventBehaviorToUtilEncodingInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'encodeJson',
                'decodeJson',
            ])
            ->getMock();
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function prepareFacade(Container $container)
    {
        $eventBehaviorBusinessFactory = new EventBehaviorBusinessFactory();
        $eventBehaviorBusinessFactory->setContainer($container);

        $this->eventBehaviorFacade = new EventBehaviorFacade();
        $this->eventBehaviorFacade->setFactory($eventBehaviorBusinessFactory);
    }

    /**
     * @return void
     */
    protected function createEntityChangeEvent()
    {
        $spyEventEntityChange = new SpyEventBehaviorEntityChange();
        $spyEventEntityChange->setProcessId(RequestIdentifier::getRequestId());
        $spyEventEntityChange->setData(json_encode($this->createEventData()));
        $spyEventEntityChange->save();
    }

    /**
     * @return void
     */
    protected function createLostEntityChangeEvent()
    {
        $spyEventEntityChange = new SpyEventBehaviorEntityChange();
        $spyEventEntityChange->setProcessId(RequestIdentifier::getRequestId());
        $spyEventEntityChange->setData(json_encode($this->createEventData()));
        $defaultTimeout = sprintf('PT%dM', EventBehaviorConfig::EVENT_ENTITY_CHANGE_TIMEOUT_MINUTE + 1);
        $date = new DateTime();
        $date->sub(new DateInterval($defaultTimeout));
        $spyEventEntityChange->setCreatedAt($date);
        $spyEventEntityChange->save();
    }

    /**
     * @return array
     */
    protected function createEventData()
    {
        return [
            EventBehavior::EVENT_CHANGE_ENTITY_NAME => 'name',
            EventBehavior::EVENT_CHANGE_ENTITY_ID => '123',
            EventBehavior::EVENT_CHANGE_ENTITY_FOREIGN_KEYS => [1, 2, 3],
            EventBehavior::EVENT_CHANGE_NAME => 'test',
            EventBehavior::EVENT_CHANGE_ENTITY_MODIFIED_COLUMNS => [],

        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function generateUtilEncodingServiceMock(Container $container)
    {
        $container[EventBehaviorDependencyProvider::SERVICE_UTIL_ENCODING] = function (Container $container) {
            $utilEncodingMock = $this->createUtilEncodingServiceBridge();
            $utilEncodingMock->expects($this->once())
                ->method('decodeJson')
                ->will($this->returnCallback(function ($data) {
                    return json_decode($data, true);
                }));
            return $utilEncodingMock;
        };

        return $container;
    }

    /**
     * @return void
     */
    protected function cleanupEventMemory()
    {
        SpyEventBehaviorEntityChangeQuery::create()->deleteAll();
    }

}
